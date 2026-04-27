<?php

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config(['scout.driver' => 'meilisearch', 'scout.meilisearch.host' => 'http://localhost:7700']);
});

afterEach(function () {
    putenv('LARAVEL_SAIL'); // unset
});

it('exits 0 when meilisearch is healthy', function () {
    Http::fake(['http://localhost:7700/health' => Http::response(['status' => 'available'], 200)]);

    $this->artisan('scoutify:doctor')->assertSuccessful();
});

it('exits 1 and prints host remediation when meilisearch unreachable on host', function () {
    Http::fake(['http://localhost:7700/health' => function () {
        throw new ConnectionException('Connection refused');
    }]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('docker run');
});

it('exits 1 and prints sail remediation when meilisearch unreachable in Sail', function () {
    putenv('LARAVEL_SAIL=1');

    Http::fake(['http://localhost:7700/health' => function () {
        throw new ConnectionException('Connection refused');
    }]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('sail down');
});

it('prints sail service missing hint when localhost in Sail', function () {
    putenv('LARAVEL_SAIL=1');

    Http::fake(['http://localhost:7700/health' => function () {
        throw new ConnectionException('Connection refused');
    }]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('MEILISEARCH_HOST=http://meilisearch:7700');
});

it('exits 1 when SCOUT_DRIVER is missing', function () {
    config(['scout.driver' => null]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('SCOUT_DRIVER');
});

it('exits 0 when algolia credentials are present', function () {
    config([
        'scout.driver' => 'algolia',
        'scout.algolia.id' => 'test-app-id',
        'scout.algolia.secret' => 'test-secret',
    ]);

    $this->artisan('scoutify:doctor')->assertSuccessful();
});

it('exits 1 when algolia credentials are missing', function () {
    config([
        'scout.driver' => 'algolia',
        'scout.algolia.id' => '',
        'scout.algolia.secret' => '',
    ]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('ALGOLIA_APP_ID');
});

it('exits 0 for unknown driver without crashing', function () {
    config(['scout.driver' => 'custom-driver']);

    $this->artisan('scoutify:doctor')->assertSuccessful();
});

it('exits 1 when meilisearch returns a non-2xx status', function () {
    Http::fake(['http://localhost:7700/health' => Http::response([], 500)]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('500');
});

it('exits 1 and prints sail-down hint when Sail + non-localhost host unreachable', function () {
    putenv('LARAVEL_SAIL=1');
    config(['scout.meilisearch.host' => 'http://meilisearch:7700']);

    Http::fake(['http://meilisearch:7700/health' => function () {
        throw new ConnectionException('Connection refused');
    }]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('sail down');
});

it('exits 1 with custom-host hint on host with non-localhost meilisearch', function () {
    config(['scout.meilisearch.host' => 'http://search.internal:7700']);

    Http::fake(['http://search.internal:7700/health' => function () {
        throw new ConnectionException('Connection refused');
    }]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('search.internal');
});

it('exits 0 when typesense is healthy', function () {
    config([
        'scout.driver' => 'typesense',
        'scout.typesense.client-settings.nodes' => [
            ['host' => 'localhost', 'port' => '8108', 'protocol' => 'http'],
        ],
    ]);

    Http::fake(['http://localhost:8108/health' => Http::response(['ok' => true], 200)]);

    $this->artisan('scoutify:doctor')->assertSuccessful();
});

it('exits 1 when typesense is unreachable', function () {
    config([
        'scout.driver' => 'typesense',
        'scout.typesense.client-settings.nodes' => [
            ['host' => 'localhost', 'port' => '8108', 'protocol' => 'http'],
        ],
    ]);

    Http::fake(['http://localhost:8108/health' => function () {
        throw new ConnectionException('Connection refused');
    }]);

    $this->artisan('scoutify:doctor')->assertFailed();
});

it('exits 1 when typesense returns a non-2xx status', function () {
    config([
        'scout.driver' => 'typesense',
        'scout.typesense.client-settings.nodes' => [
            ['host' => 'localhost', 'port' => '8108', 'protocol' => 'http'],
        ],
    ]);

    Http::fake(['http://localhost:8108/health' => Http::response([], 503)]);

    $this->artisan('scoutify:doctor')->assertFailed();
});
