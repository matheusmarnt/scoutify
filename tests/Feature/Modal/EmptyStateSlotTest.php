<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\ScoutifyManager;
use Matheusmarnt\Scoutify\Support\SlotContext;
use Matheusmarnt\Scoutify\Support\UiConfig;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('renders default empty-state component when no slot is configured', function () {
    // Use the description text — unique to empty-state.blade.php, not in sr-only region
    Livewire::test(Modal::class)
        ->set('query', 'xyznonexistent123')
        ->assertSee('Try different terms');
});

it('renders Closure slot output instead of default empty-state', function () {
    app(ScoutifyManager::class)->configureUi(fn (UiConfig $ui) => $ui->slot(
        'empty-state',
        fn (SlotContext $ctx) => '<div class="custom-empty-sentinel">Nothing found</div>'
    ));

    // "Try different terms" is from no_results_hint — only in default empty-state component
    Livewire::test(Modal::class)
        ->set('query', 'xyznonexistent123')
        ->assertSeeHtml('custom-empty-sentinel')
        ->assertDontSee('Try different terms');
});

it('passes $ctx->query to the empty-state slot Closure', function () {
    app(ScoutifyManager::class)->configureUi(fn (UiConfig $ui) => $ui->slot(
        'empty-state',
        fn (SlotContext $ctx) => '<span class="query-echo">'.e($ctx->query).'</span>'
    ));

    Livewire::test(Modal::class)
        ->set('query', 'unique-search-term-xyz')
        ->assertSeeHtml('unique-search-term-xyz');
});

it('empty-state slot does not activate on idle state (blank query)', function () {
    app(ScoutifyManager::class)->configureUi(fn (UiConfig $ui) => $ui->slot(
        'empty-state',
        fn (SlotContext $ctx) => '<div class="custom-empty-sentinel">Nothing found</div>'
    ));

    Livewire::test(Modal::class)
        ->set('query', '')
        ->assertDontSeeHtml('custom-empty-sentinel');
});

it('empty-state slot does not activate when results are present', function () {
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->timestamps();
    });

    Article::create(['name' => 'Matching Result']);

    app(ScoutifyManager::class)->types()->register(Article::class,
        label: 'Articles', icon: 'heroicon-o-document', color: 'blue'
    );

    app(ScoutifyManager::class)->configureUi(fn (UiConfig $ui) => $ui->slot(
        'empty-state',
        fn (SlotContext $ctx) => '<div class="custom-empty-sentinel">Nothing found</div>'
    ));

    Livewire::test(Modal::class)
        ->set('query', 'Matching')
        ->assertSeeHtml('Matching')
        ->assertDontSeeHtml('custom-empty-sentinel');
});
