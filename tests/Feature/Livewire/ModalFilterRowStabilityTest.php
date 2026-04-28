<?php

use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;

it('filter row is visible when query is filled and all type-chip results are empty', function () {
    config()->set('scoutify.types', []);

    $registry = app(GlobalSearchRegistry::class);
    $registry->register('App\Models\User', [
        'key' => 'User',
        'label' => 'Usuários',
        'icon' => 'heroicon-o-user',
        'color' => 'indigo',
    ]);

    $component = Livewire::test(Modal::class)
        ->set('query', 'ana')
        ->set('activeTypes', ['User']);

    $component->assertSee('Usuários', false);
});

it('filter row is hidden when query is blank and availableTypes <= 1', function () {
    config()->set('scoutify.types', []);

    $registry = app(GlobalSearchRegistry::class);
    $registry->register('App\Models\User', [
        'key' => 'User',
        'label' => 'Usuários',
        'icon' => 'heroicon-o-user',
        'color' => 'indigo',
    ]);

    $component = Livewire::test(Modal::class)
        ->set('query', '');

    $component->assertDontSee('Usuários', false);
});

it('filter row is visible when availableTypes > 1 even with empty results', function () {
    config()->set('scoutify.types', []);

    $registry = app(GlobalSearchRegistry::class);
    $registry->register('App\Models\User', [
        'key' => 'User',
        'label' => 'Usuários',
        'icon' => 'heroicon-o-user',
        'color' => 'indigo',
    ]);
    $registry->register('App\Models\Post', [
        'key' => 'Post',
        'label' => 'Posts',
        'icon' => 'heroicon-o-document',
        'color' => 'emerald',
    ]);

    $component = Livewire::test(Modal::class)
        ->set('query', '');

    $component->assertSee('Usuários', false);
});
