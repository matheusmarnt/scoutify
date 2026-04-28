<?php

use Matheusmarnt\Scoutify\Support\TypeManifest;

it('elvis operator falls back to app Models when discovery paths is empty array', function () {
    config()->set('scoutify.discovery.paths', []);

    $paths = config('scoutify.discovery.paths') ?: [app_path('Models')];

    expect($paths)->toBe([app_path('Models')]);
});

it('elvis operator keeps explicit paths when discovery paths is non-empty', function () {
    $custom = ['/tmp/custom-models'];
    config()->set('scoutify.discovery.paths', $custom);

    $paths = config('scoutify.discovery.paths') ?: [app_path('Models')];

    expect($paths)->toBe($custom);
});

it('build does not crash when fallback path is nonexistent', function () {
    config()->set('scoutify.discovery.paths', []);

    $manifest = TypeManifest::build();

    expect($manifest)->toBeArray();
});
