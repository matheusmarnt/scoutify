<?php

use Illuminate\Support\HtmlString;
use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\ScoutifyManager;
use Matheusmarnt\Scoutify\Support\SlotContext;
use Matheusmarnt\Scoutify\Support\UiConfig;

it('renders header-trailing slot registered with hyphen notation', function () {
    app(ScoutifyManager::class)->configureUi(fn (UiConfig $ui) => $ui->slot(
        'header-trailing',
        fn (SlotContext $ctx) => new HtmlString('<span class="slot-hyphen-sentinel">Trailing</span>')
    ));

    Livewire::test(Modal::class)
        ->set('query', 'test')
        ->assertSeeHtml('slot-hyphen-sentinel');
});

it('closure with $ctx->results->count() does not fatal', function () {
    app(ScoutifyManager::class)->configureUi(fn (UiConfig $ui) => $ui->slot(
        'header-trailing',
        fn (SlotContext $ctx) => new HtmlString('<span class="count-sentinel">'.e($ctx->results->count()).'</span>')
    ));

    Livewire::test(Modal::class)
        ->set('query', 'xyznonexistent123')
        ->assertSeeHtml('count-sentinel');
});

it('empty-state slot continues working after normalization fix', function () {
    app(ScoutifyManager::class)->configureUi(fn (UiConfig $ui) => $ui->slot(
        'empty-state',
        fn (SlotContext $ctx) => new HtmlString('<div class="custom-empty-regression">Custom empty</div>')
    ));

    Livewire::test(Modal::class)
        ->set('query', 'xyznonexistent123')
        ->assertSeeHtml('custom-empty-regression')
        ->assertDontSee('Try different terms');
});
