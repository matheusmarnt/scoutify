<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

beforeEach(function () {
    Process::fake();
    $this->tmpDir = sys_get_temp_dir().'/scoutify-docker-hardening-'.uniqid();
    mkdir($this->tmpDir, 0755, true);
    mkdir($this->tmpDir.'/config', 0755, true);
    file_put_contents($this->tmpDir.'/.env', '');

    $this->app->setBasePath($this->tmpDir);

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

// --- Stub content ---

it('compose.scoutify.yaml stub does not contain latest tag', function () {
    $stub = file_get_contents(__DIR__.'/../../../stubs/compose.scoutify.yaml');

    expect($stub)->not->toContain(':latest');
});

it('compose.scoutify.typesense.yaml stub does not contain latest tag', function () {
    $stub = file_get_contents(__DIR__.'/../../../stubs/compose.scoutify.typesense.yaml');

    expect($stub)->not->toContain(':latest');
});

// --- Host path: pinned docker run version ---

it('host mode meilisearch docker run uses pinned version not latest', function () {
    $this->artisan('scoutify:install', ['--driver' => 'meilisearch'])
        ->expectsOutputToContain('v1.12.8')
        ->doesntExpectOutputToContain('meilisearch:latest');
});

it('host mode typesense docker run uses pinned version not latest', function () {
    $this->artisan('scoutify:install', ['--driver' => 'typesense'])
        ->expectsOutputToContain('27.1')
        ->doesntExpectOutputToContain('typesense:latest');
});

// --- Docker path: condition: service_healthy in note ---

it('docker mode meilisearch note includes condition service_healthy', function () {
    file_put_contents($this->tmpDir.'/compose.yaml', "services:\n  app:\n    image: php:8.3\n");

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch', '--patch-compose' => 'skip'])
        ->expectsQuestion('Which service in compose.yaml is your application service?', 'app')
        ->expectsOutputToContain('condition: service_healthy');
});

it('docker mode typesense note includes condition service_healthy', function () {
    file_put_contents($this->tmpDir.'/compose.yaml', "services:\n  app:\n    image: php:8.3\n");

    $this->artisan('scoutify:install', ['--driver' => 'typesense', '--patch-compose' => 'skip'])
        ->expectsQuestion('Which service in compose.yaml is your application service?', 'app')
        ->expectsOutputToContain('condition: service_healthy');
});

// --- Sail path: condition: service_healthy instruction ---

it('sail mode meilisearch outputs condition service_healthy instruction', function () {
    mkdir($this->tmpDir.'/vendor/laravel/sail', 0755, true);

    $this->artisan('scoutify:install', ['--driver' => 'meilisearch'])
        ->expectsOutputToContain('condition: service_healthy');
});

it('sail mode typesense outputs condition service_healthy instruction', function () {
    mkdir($this->tmpDir.'/vendor/laravel/sail', 0755, true);

    $this->artisan('scoutify:install', ['--driver' => 'typesense'])
        ->expectsOutputToContain('condition: service_healthy');
});
