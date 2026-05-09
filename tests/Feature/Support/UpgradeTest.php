<?php

use Matheusmarnt\Scoutify\ScoutifyServiceProvider;

function callAssertNoLegacyConfigKeys(): void
{
    $provider = new ScoutifyServiceProvider(app());
    $method = (new ReflectionClass($provider))->getMethod('assertNoLegacyConfigKeys');
    $method->setAccessible(true);
    $method->invoke($provider);
}

it('does not throw when config has no legacy keys', function () {
    expect(fn () => callAssertNoLegacyConfigKeys())->not->toThrow(RuntimeException::class);
});

it('throws when icon_prefix is in config', function () {
    config(['scoutify.icon_prefix' => 'ri-']);

    expect(fn () => callAssertNoLegacyConfigKeys())
        ->toThrow(RuntimeException::class, 'icon_prefix');
});

it('throws when types is in config', function () {
    config(['scoutify.types' => ['App\Models\User' => ['label' => 'Users']]]);

    expect(fn () => callAssertNoLegacyConfigKeys())
        ->toThrow(RuntimeException::class, 'types');
});

it('throws when classes is in config', function () {
    config(['scoutify.classes' => ['trigger' => 'flex items-center']]);

    expect(fn () => callAssertNoLegacyConfigKeys())
        ->toThrow(RuntimeException::class, 'classes');
});

it('throws when colors is in config', function () {
    config(['scoutify.colors' => ['brand' => ['light' => 'bg-blue-100']]]);

    expect(fn () => callAssertNoLegacyConfigKeys())
        ->toThrow(RuntimeException::class, 'colors');
});

it('throws when modal.ui is in config', function () {
    config(['scoutify.modal' => ['ui' => ['show_type_chips' => false]]]);

    expect(fn () => callAssertNoLegacyConfigKeys())
        ->toThrow(RuntimeException::class, 'modal.ui');
});

it('error message includes the upgrade URL', function () {
    config(['scoutify.icon_prefix' => 'ri-']);

    expect(fn () => callAssertNoLegacyConfigKeys())
        ->toThrow(RuntimeException::class, 'upgrading/v2');
});
