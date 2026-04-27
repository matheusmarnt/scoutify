<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->tmpDir = sys_get_temp_dir().'/scoutify-install-'.uniqid();
    mkdir($this->tmpDir, 0755, true);
    mkdir($this->tmpDir.'/config', 0755, true);
    file_put_contents($this->tmpDir.'/.env', '');

    $this->app->setBasePath($this->tmpDir);

    // Doctor runs at end of install — silence its HTTP calls
    Http::fake(['*/health' => Http::response(['status' => 'available'], 200)]);
});

afterEach(function () {
    if (is_dir($this->tmpDir)) {
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->tmpDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $file) {
            $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
        }
        rmdir($this->tmpDir);
    }
});

it('scoutify:install runs without error when given a driver option', function () {
    $this->artisan('scoutify:install', ['--driver' => 'meilisearch'])
        ->assertSuccessful();
});

it('scoutify:install fails with an unknown driver', function () {
    $this->artisan('scoutify:install', ['--driver' => 'unknown'])
        ->assertFailed();
});

it('sets SCOUT_DRIVER in .env', function () {
    $this->artisan('scoutify:install', ['--driver' => 'meilisearch']);

    expect(file_get_contents($this->tmpDir.'/.env'))->toContain('SCOUT_DRIVER=meilisearch');
});

it('host mode writes MEILISEARCH_HOST=http://localhost:7700 when no Sail or compose', function () {
    $this->artisan('scoutify:install', ['--driver' => 'meilisearch']);

    expect(file_get_contents($this->tmpDir.'/.env'))->toContain('MEILISEARCH_HOST=http://localhost:7700');
});

it('docker mode creates docker-compose.scoutify.yml and sets container host', function () {
    file_put_contents($this->tmpDir.'/docker-compose.yml', "services:\n  app:\n    image: php:8.3\n");

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch']);

    expect(file_exists($this->tmpDir.'/docker-compose.scoutify.yml'))->toBeTrue();
    expect(file_get_contents($this->tmpDir.'/.env'))->toContain('MEILISEARCH_HOST=http://meilisearch:7700');
});

it('docker mode does not overwrite existing docker-compose.scoutify.yml', function () {
    file_put_contents($this->tmpDir.'/docker-compose.yml', "services:\n  app:\n    image: php:8.3\n");
    file_put_contents($this->tmpDir.'/docker-compose.scoutify.yml', 'existing-content');

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch']);

    expect(file_get_contents($this->tmpDir.'/docker-compose.scoutify.yml'))->toBe('existing-content');
});

it('sail mode sets MEILISEARCH_HOST=http://meilisearch:7700', function () {
    mkdir($this->tmpDir.'/vendor/laravel/sail', 0755, true);
    // Sail not actually installed — sail:add command absent — but env var should still be set

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch']);

    expect(file_get_contents($this->tmpDir.'/.env'))->toContain('MEILISEARCH_HOST=http://meilisearch:7700');
});

it('sail mode upgrades existing localhost default to meilisearch host', function () {
    mkdir($this->tmpDir.'/vendor/laravel/sail', 0755, true);
    file_put_contents($this->tmpDir.'/.env', "MEILISEARCH_HOST=http://localhost:7700\n");

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch']);

    $env = file_get_contents($this->tmpDir.'/.env');
    expect($env)->toContain('MEILISEARCH_HOST=http://meilisearch:7700');
    expect($env)->not->toContain('MEILISEARCH_HOST=http://localhost:7700');
});

it('does not overwrite a custom MEILISEARCH_HOST value', function () {
    mkdir($this->tmpDir.'/vendor/laravel/sail', 0755, true);
    file_put_contents($this->tmpDir.'/.env', "MEILISEARCH_HOST=http://custom-host:9999\n");

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch']);

    expect(file_get_contents($this->tmpDir.'/.env'))->toContain('MEILISEARCH_HOST=http://custom-host:9999');
});

it('sail mode algolia writes ALGOLIA_APP_ID and ALGOLIA_SECRET placeholders', function () {
    mkdir($this->tmpDir.'/vendor/laravel/sail', 0755, true);

    $this->artisan('scoutify:install', ['--driver' => 'algolia'])
        ->assertSuccessful();

    $env = file_get_contents($this->tmpDir.'/.env');
    expect($env)->toContain('ALGOLIA_APP_ID=');
    expect($env)->toContain('ALGOLIA_SECRET=');
    expect($env)->not->toContain('MEILISEARCH_HOST');
});

it('docker mode algolia writes credentials and skips compose stub', function () {
    file_put_contents($this->tmpDir.'/docker-compose.yml', "services:\n  app:\n    image: php:8.3\n");

    $this->artisan('scoutify:install', ['--driver' => 'algolia'])
        ->assertSuccessful();

    expect(file_exists($this->tmpDir.'/docker-compose.scoutify.yml'))->toBeFalse();
    expect(file_get_contents($this->tmpDir.'/.env'))->toContain('ALGOLIA_APP_ID=');
});

it('host mode algolia writes credentials and no MEILISEARCH_HOST', function () {
    $this->artisan('scoutify:install', ['--driver' => 'algolia'])
        ->assertSuccessful();

    $env = file_get_contents($this->tmpDir.'/.env');
    expect($env)->toContain('ALGOLIA_APP_ID=');
    expect($env)->not->toContain('MEILISEARCH_HOST');
});

it('host mode typesense writes TYPESENSE_HOST=localhost and port', function () {
    $this->artisan('scoutify:install', ['--driver' => 'typesense']);

    $env = file_get_contents($this->tmpDir.'/.env');
    expect($env)->toContain('TYPESENSE_HOST=localhost');
    expect($env)->toContain('TYPESENSE_PORT=8108');
});

it('sail mode typesense sets TYPESENSE_HOST=typesense', function () {
    mkdir($this->tmpDir.'/vendor/laravel/sail', 0755, true);

    $this->artisan('scoutify:install', ['--driver' => 'typesense']);

    expect(file_get_contents($this->tmpDir.'/.env'))->toContain('TYPESENSE_HOST=typesense');
});

it('docker mode typesense creates docker-compose.scoutify.yml and sets container host', function () {
    file_put_contents($this->tmpDir.'/docker-compose.yml', "services:\n  app:\n    image: php:8.3\n");

    $this->artisan('scoutify:install', ['--driver' => 'typesense']);

    expect(file_exists($this->tmpDir.'/docker-compose.scoutify.yml'))->toBeTrue();
    expect(file_get_contents($this->tmpDir.'/.env'))->toContain('TYPESENSE_HOST=typesense');
});

it('does not write SCOUT_DRIVER when key already exists in .env', function () {
    file_put_contents($this->tmpDir.'/.env', "SCOUT_DRIVER=typesense\n");

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch']);

    expect(file_get_contents($this->tmpDir.'/.env'))->toContain('SCOUT_DRIVER=typesense');
    expect(substr_count(file_get_contents($this->tmpDir.'/.env'), 'SCOUT_DRIVER='))->toBe(1);
});

// Regression: sail:add was previously called with an array, causing TypeError in Sail's explode()
it('sail mode passes services to sail:add as a string not an array', function () {
    mkdir($this->tmpDir.'/vendor/laravel/sail', 0755, true);

    $captured = null;
    Artisan::command('sail:add {services?}', function () use (&$captured) {
        $captured = $this->argument('services');
    });

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch']);

    expect($captured)->toBeString()->toBe('meilisearch');
});

it('sail mode detects existing service in compose.yaml and skips sail:add', function () {
    mkdir($this->tmpDir.'/vendor/laravel/sail', 0755, true);
    file_put_contents($this->tmpDir.'/compose.yaml', "services:\n  meilisearch:\n    image: getmeili/meilisearch\n");

    $called = false;
    Artisan::command('sail:add {services?}', function () use (&$called) {
        $called = true;
    });

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch']);

    expect($called)->toBeFalse();
});
