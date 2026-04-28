<?php

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake(['*/health' => Http::response(['status' => 'available'], 200)]);
});

it('emits prefix search warning when driver is meilisearch', function () {
    config(['scout.driver' => 'meilisearch', 'scout.meilisearch.host' => 'http://localhost:7700']);

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('word-boundary prefix search');
});

it('prefix search warning not present when driver is database', function () {
    config(['scout.driver' => 'database']);

    $this->artisan('scoutify:doctor')
        ->doesntExpectOutputToContain('word-boundary prefix search');
});

it('prefix search warning not present when driver is algolia', function () {
    config([
        'scout.driver' => 'algolia',
        'scout.algolia.id' => 'fake-id',
        'scout.algolia.secret' => 'fake-secret',
    ]);

    $this->artisan('scoutify:doctor')
        ->doesntExpectOutputToContain('word-boundary prefix search');
});

it('warning mentions globalSearchBuilder override as remediation', function () {
    config(['scout.driver' => 'meilisearch', 'scout.meilisearch.host' => 'http://localhost:7700']);

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('globalSearchBuilder');
});
