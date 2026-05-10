<?php

use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Matheusmarnt\Scoutify\Facades\Scoutify;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\ScoutifyManager;

it('getAccent returns null by default', function () {
    expect(app(ScoutifyManager::class)->theme()->getAccent())->toBeNull();
});

it('accent setter stores value and getter returns it', function () {
    app(ScoutifyManager::class)->theme()->accent('#7c3aed');

    expect(app(ScoutifyManager::class)->theme()->getAccent())->toBe('#7c3aed');
});

it('modal wrapper contains style with --scoutify-accent when accent is configured', function () {
    Artisan::call('view:clear');

    app(ScoutifyManager::class)->theme()->accent('#7c3aed');

    $html = Livewire::test(Modal::class)->html();

    expect($html)->toContain('--scoutify-accent: #7c3aed');
});

it('modal wrapper has no style attribute when accent is not configured', function () {
    Artisan::call('view:clear');

    $html = Livewire::test(Modal::class)->html();

    expect($html)->not->toContain('--scoutify-accent');
});

it('accent supports fluent chaining with other theme methods', function () {
    $theme = Scoutify::theme()
        ->accent('var(--color-violet-600)')
        ->dialogPanel('relative w-full md:max-w-2xl');

    expect($theme->getAccent())->toBe('var(--color-violet-600)')
        ->and($theme->getDialogPanel())->toBe('relative w-full md:max-w-2xl');
});
