<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Symfony\Component\Yaml\Yaml;

beforeEach(function () {
    Process::fake();
    $this->tmpDir = sys_get_temp_dir().'/scoutify-install-patch-'.uniqid();
    mkdir($this->tmpDir, 0755, true);
    mkdir($this->tmpDir.'/config', 0755, true);
    mkdir($this->tmpDir.'/vendor/laravel/sail', 0755, true);
    file_put_contents($this->tmpDir.'/.env', '');

    file_put_contents($this->tmpDir.'/compose.yaml', <<<'YAML'
services:
    laravel.test:
        image: 'sail-8.3/app'
        depends_on:
            - mysql
            - meilisearch
    mysql:
        image: 'mysql/mysql-server:8.0'
    meilisearch:
        image: 'getmeili/meilisearch:v1.12.8'
YAML);

    $this->app->setBasePath($this->tmpDir);
    Http::fake(['*/health' => Http::response(['status' => 'available'], 200)]);
});

afterEach(function () {
    if (! is_dir($this->tmpDir)) {
        return;
    }
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($this->tmpDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($it as $file) {
        $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
    }
    rmdir($this->tmpDir);
});

it('sail mode patches compose.yaml when prompt accepted', function () {
    $this->artisan('scoutify:install', ['--driver' => 'meilisearch'])
        ->expectsConfirmation('Patch compose.yaml automatically to add condition: service_healthy?', 'yes');

    $parsed = Yaml::parseFile($this->tmpDir.'/compose.yaml');
    expect($parsed['services']['laravel.test']['depends_on']['meilisearch'])
        ->toBe(['condition' => 'service_healthy']);

    expect(glob($this->tmpDir.'/compose.yaml.scoutify-backup-*'))->toHaveCount(1);
});

it('sail mode skips patch and prints manual instructions when prompt declined', function () {
    $original = file_get_contents($this->tmpDir.'/compose.yaml');

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch'])
        ->expectsConfirmation('Patch compose.yaml automatically to add condition: service_healthy?', 'no')
        ->expectsOutputToContain('condition: service_healthy');

    expect(file_get_contents($this->tmpDir.'/compose.yaml'))->toBe($original);
    expect(glob($this->tmpDir.'/compose.yaml.scoutify-backup-*'))->toBeEmpty();
});

it('--patch-compose=auto skips prompt and patches', function () {
    $this->artisan('scoutify:install', ['--driver' => 'meilisearch', '--patch-compose' => 'auto'])
        ->doesntExpectOutputToContain('Patch compose.yaml automatically?');

    $parsed = Yaml::parseFile($this->tmpDir.'/compose.yaml');
    expect($parsed['services']['laravel.test']['depends_on']['meilisearch'])
        ->toBe(['condition' => 'service_healthy']);
});

it('--patch-compose=skip skips prompt and does not patch', function () {
    $original = file_get_contents($this->tmpDir.'/compose.yaml');

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch', '--patch-compose' => 'skip']);

    expect(file_get_contents($this->tmpDir.'/compose.yaml'))->toBe($original);
});

it('is idempotent on second run', function () {
    $this->artisan('scoutify:install', ['--driver' => 'meilisearch', '--patch-compose' => 'auto']);
    $afterFirst = file_get_contents($this->tmpDir.'/compose.yaml');

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch', '--patch-compose' => 'auto']);

    expect(file_get_contents($this->tmpDir.'/compose.yaml'))->toBe($afterFirst);
    expect(glob($this->tmpDir.'/compose.yaml.scoutify-backup-*'))->toHaveCount(1);
});

it('docker mode (non-sail) patches compose.yaml when prompt accepted, prompting for service name', function () {
    rmdir($this->tmpDir.'/vendor/laravel/sail');
    rmdir($this->tmpDir.'/vendor/laravel');
    rmdir($this->tmpDir.'/vendor');

    file_put_contents($this->tmpDir.'/compose.yaml', <<<'YAML'
services:
    app:
        image: 'php:8.3-fpm'
    meilisearch:
        image: 'getmeili/meilisearch:v1.12.8'
YAML);

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch'])
        ->expectsQuestion('Which service in compose.yaml is your application service?', 'app')
        ->expectsConfirmation('Patch compose.yaml automatically to add condition: service_healthy?', 'yes');

    $parsed = Yaml::parseFile($this->tmpDir.'/compose.yaml');
    expect($parsed['services']['app']['depends_on']['meilisearch'])
        ->toBe(['condition' => 'service_healthy']);
});
