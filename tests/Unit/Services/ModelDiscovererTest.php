<?php

use Matheusmarnt\Scoutify\Services\ModelDiscoverer;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('returns empty array when path does not exist', function () {
    $discoverer = new ModelDiscoverer('/non/existent/path');
    expect($discoverer->discover())->toBe([]);
});

it('can be constructed with custom path', function () {
    $discoverer = new ModelDiscoverer('/tmp');
    expect($discoverer)->toBeInstanceOf(ModelDiscoverer::class);
});

it('uses custom namespace when provided', function () {
    $discoverer = new ModelDiscoverer('/tmp', 'Custom\\Models\\');
    expect($discoverer)->toBeInstanceOf(ModelDiscoverer::class);
});

it('discovers eloquent models in a directory', function () {
    $path = realpath(__DIR__.'/../../Fixtures/Models');
    $discoverer = new ModelDiscoverer($path, 'Matheusmarnt\\Scoutify\\Tests\\Fixtures\\Models\\');

    expect($discoverer->discover())->toContain(Article::class);
});

it('skips non-model php classes in a directory', function () {
    $path = realpath(__DIR__.'/../../../src/Services');
    $discoverer = new ModelDiscoverer($path, 'Matheusmarnt\\Scoutify\\Services\\');

    expect($discoverer->discover())->toBe([]);
});

it('make() creates instance with defaults', function () {
    expect(ModelDiscoverer::make())->toBeInstanceOf(ModelDiscoverer::class);
});

it('make() accepts custom basePath and namespace', function () {
    $discoverer = ModelDiscoverer::make('/non/existent', 'Custom\\');

    expect($discoverer->discover())->toBe([]);
});
