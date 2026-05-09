<?php

use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\ScoutifyManager;
use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;

it('availableTypes is empty when both registry and fluent API are empty', function () {
    $component = Livewire::test(Modal::class);

    expect($component->instance()->availableTypes())->toBe([]);
});

it('availableTypes returns types from registry', function () {
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

it('availableTypes fluent API overrides registry metadata per key', function () {
    $registry = app(GlobalSearchRegistry::class);
    $registry->register('App\Models\User', [
        'key' => 'User',
        'label' => 'Users',
        'icon' => 'heroicon-o-user',
        'color' => 'gray',
    ]);

    app(ScoutifyManager::class)->types()->register('App\Models\User', label: 'Members', color: 'indigo');

    $component = Livewire::test(Modal::class);
    $types = $component->instance()->availableTypes();

    expect($types)->toHaveCount(1)
        ->and($types[0]['color'])->toBe('indigo')
        ->and($types[0]['label'])->toBe('Members');
});
