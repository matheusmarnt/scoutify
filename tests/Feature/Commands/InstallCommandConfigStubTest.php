<?php

it('published config has discovery paths enabled by default', function () {
    $paths = config('scoutify.discovery.paths');

    expect($paths)->toContain(app_path('Models'));
});

it('discovery paths fallback does not trigger when config has app Models', function () {
    $paths = config('scoutify.discovery.paths') ?: [app_path('Models')];

    expect($paths)->toBe([app_path('Models')]);
});
