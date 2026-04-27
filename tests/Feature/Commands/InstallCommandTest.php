<?php

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

it('sail mode with non-meilisearch driver skips sail-specific config', function () {
    mkdir($this->tmpDir.'/vendor/laravel/sail', 0755, true);

    $this->artisan('scoutify:install', ['--driver' => 'algolia'])
        ->assertSuccessful();

    expect(file_get_contents($this->tmpDir.'/.env'))->not->toContain('MEILISEARCH_HOST');
});

it('docker mode with non-meilisearch driver skips docker-compose stub', function () {
    file_put_contents($this->tmpDir.'/docker-compose.yml', "services:\n  app:\n    image: php:8.3\n");

    $this->artisan('scoutify:install', ['--driver' => 'algolia'])
        ->assertSuccessful();

    expect(file_exists($this->tmpDir.'/docker-compose.scoutify.yml'))->toBeFalse();
});

it('host mode with non-meilisearch driver does not write MEILISEARCH_HOST', function () {
    $this->artisan('scoutify:install', ['--driver' => 'algolia'])
        ->assertSuccessful();

    expect(file_get_contents($this->tmpDir.'/.env'))->not->toContain('MEILISEARCH_HOST');
});

it('does not write SCOUT_DRIVER when key already exists in .env', function () {
    file_put_contents($this->tmpDir.'/.env', "SCOUT_DRIVER=typesense\n");

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch']);

    expect(file_get_contents($this->tmpDir.'/.env'))->toContain('SCOUT_DRIVER=typesense');
    expect(substr_count(file_get_contents($this->tmpDir.'/.env'), 'SCOUT_DRIVER='))->toBe(1);
});
