<?php

use Matheusmarnt\Scoutify\Services\ModelDiscoverer;

it('returns empty array when path does not exist', function () {
    $discoverer = new ModelDiscoverer('/non/existent/path');
    expect($discoverer->discover())->toBe([]);
});

it('can be constructed with custom path', function () {
    $discoverer = new ModelDiscoverer('/tmp');
    expect($discoverer)->toBeInstanceOf(ModelDiscoverer::class);
});
