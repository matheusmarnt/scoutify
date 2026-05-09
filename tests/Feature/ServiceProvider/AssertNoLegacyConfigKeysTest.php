<?php

use Matheusmarnt\Scoutify\ScoutifyServiceProvider;

function bootProvider(): void
{
    $provider = new ScoutifyServiceProvider(app());
    $provider->register();
    $provider->boot();
}

test('exception message contains upgrade steps when legacy key detected', function () {
    config()->set('scoutify.classes', ['input' => 'foo']);

    expect(fn () => bootProvider())
        ->toThrow(RuntimeException::class, 'vendor:publish --tag=scoutify-config');
});

test('exception message contains upgrade guide URL', function () {
    config()->set('scoutify.icon_prefix', 'heroicon-o-');

    expect(fn () => bootProvider())
        ->toThrow(RuntimeException::class, 'matheusmarnt.github.io/scoutify/upgrading/v2');
});

test('exception lists all detected legacy keys', function () {
    config()->set('scoutify.icon_prefix', 'heroicon-o-');
    config()->set('scoutify.types', []);

    expect(fn () => bootProvider())
        ->toThrow(RuntimeException::class, 'icon_prefix, types');
});

test('exception detects modal.ui legacy key', function () {
    config()->set('scoutify.modal.ui', ['show_type_chips' => false]);

    expect(fn () => bootProvider())
        ->toThrow(RuntimeException::class, 'modal.ui');
});

test('no exception when config has only v2 keys', function () {
    expect(fn () => bootProvider())
        ->not->toThrow(RuntimeException::class);
});
