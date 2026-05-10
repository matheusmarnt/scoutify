<?php

use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\ScoutifyManager;
use Matheusmarnt\Scoutify\Support\UiConfig;

it('idleHintVisible returns true by default', function () {
    expect(app(ScoutifyManager::class)->resolveUi()->idleHintVisible())->toBeTrue();
});

it('idle prompt is visible when showIdleHint is not configured', function () {
    Livewire::test(Modal::class)
        ->set('query', '')
        ->assertSee('Type to search across the entire system');
});

it('idle prompt is absent when showIdleHint is set to false', function () {
    app(ScoutifyManager::class)->configureUi(fn (UiConfig $ui) => $ui->showIdleHint(false));

    Livewire::test(Modal::class)
        ->set('query', '')
        ->assertDontSee('Type to search across the entire system');
});

it('idle prompt is visible when showIdleHint is explicitly set to true', function () {
    app(ScoutifyManager::class)->configureUi(fn (UiConfig $ui) => $ui->showIdleHint(true));

    Livewire::test(Modal::class)
        ->set('query', '')
        ->assertSee('Type to search across the entire system');
});

it('showIdleHint false suppresses only the prompt paragraph, not the icon or structure', function () {
    app(ScoutifyManager::class)->configureUi(fn (UiConfig $ui) => $ui->showIdleHint(false));

    // px-6 py-12 is the idle-state icon wrapper — always rendered regardless of showIdleHint
    Livewire::test(Modal::class)
        ->set('query', '')
        ->assertSeeHtml('px-6 py-12')
        ->assertDontSee('Type to search across the entire system');
});
