<?php

use Matheusmarnt\Scoutify\Support\TypeManifest;

beforeEach(function () {
    TypeManifest::forget();
});

afterEach(function () {
    TypeManifest::forget();
});

it('empty manifest triggers rebuild condition', function () {
    TypeManifest::write([]);

    expect(is_file(TypeManifest::path()))->toBeTrue();
    expect(TypeManifest::load())->toBe([]);

    $needsRebuild = ! is_file(TypeManifest::path()) || empty(TypeManifest::load());

    expect($needsRebuild)->toBeTrue();
});

it('absent manifest triggers rebuild condition', function () {
    $needsRebuild = ! is_file(TypeManifest::path()) || empty(TypeManifest::load());

    expect($needsRebuild)->toBeTrue();
});

it('non-empty manifest does not trigger rebuild condition', function () {
    TypeManifest::write([
        'App\Models\User' => ['key' => 'User', 'icon' => 'x', 'color' => 'gray'],
    ]);

    $needsRebuild = ! is_file(TypeManifest::path()) || empty(TypeManifest::load());

    expect($needsRebuild)->toBeFalse();
});
