<?php

use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;

it('availableTypes is empty when both registry and config are empty', function () {
    config()->set('scoutify.types', []);

    $component = Livewire::test(Modal::class);

    expect($component->instance()->availableTypes())->toBe([]);
});

it('availableTypes returns types from registry when config is empty', function () {
    config()->set('scoutify.types', []);

    $registry = app(GlobalSearchRegistry::class);
    $registry->register('App\Models\User', [
        'key' => 'User',
        'label' => 'Users',
        'icon' => 'heroicon-o-user',
        'color' => 'indigo',
    ]);

    $component = Livewire::test(Modal::class);
    $types = $component->instance()->availableTypes();

    expect($types)->toHaveCount(1)
        ->and($types[0]['key'])->toBe('User')
        ->and($types[0]['label'])->toBe('Users');
});

it('availableTypes config overrides registry metadata per key', function () {
    $registry = app(GlobalSearchRegistry::class);
    $registry->register('App\Models\User', [
        'key' => 'User',
        'label' => 'Users',
        'icon' => 'heroicon-o-user',
        'color' => 'gray',
    ]);

    config()->set('scoutify.types', [
        'App\Models\User' => ['color' => 'indigo', 'label' => 'Members'],
    ]);

    $component = Livewire::test(Modal::class);
    $types = $component->instance()->availableTypes();

    expect($types)->toHaveCount(1)
        ->and($types[0]['color'])->toBe('indigo')
        ->and($types[0]['label'])->toBe('Members');
});
