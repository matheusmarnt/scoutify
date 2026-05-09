<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Matheusmarnt\Scoutify\Facades\Scoutify;
use Matheusmarnt\Scoutify\ScoutifyManager;
use Matheusmarnt\Scoutify\Services\SearchAggregator;
use Matheusmarnt\Scoutify\Support\UiConfig;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('Scoutify facade resolves to the ScoutifyManager singleton', function () {
    expect(Scoutify::types())->toBe(app(ScoutifyManager::class)->types());
});

it('types registered via fluent API are retrievable from types()', function () {
    Scoutify::types()->register(Article::class, label: 'Articles', icon: 'heroicon-o-document', color: 'blue');

    expect(Scoutify::types()->get(Article::class))->not->toBeNull()
        ->and(Scoutify::types()->get(Article::class)['label'])->toBe('Articles')
        ->and(Scoutify::types()->get(Article::class)['color'])->toBe('blue');
});

it('iconPrefix is stored and retrievable', function () {
    Scoutify::types()->iconPrefix('ri-');

    expect(Scoutify::types()->getIconPrefix())->toBe('ri-');
});

it('configureUi closure is applied when resolveUi is called', function () {
    app(ScoutifyManager::class)->configureUi(fn (UiConfig $ui) => $ui->showTypeChips(false)->showHintBar(false));

    $ui = app(ScoutifyManager::class)->resolveUi();

    expect($ui->typeChipsVisible())->toBeFalse()
        ->and($ui->hintBarVisible())->toBeFalse();
});

it('resolveUi returns all-visible defaults when no closure is registered', function () {
    $ui = app(ScoutifyManager::class)->resolveUi();

    expect($ui->typeChipsVisible())->toBeTrue()
        ->and($ui->hintBarVisible())->toBeTrue()
        ->and($ui->toggleOnlyActiveVisible())->toBeTrue()
        ->and($ui->toggleIncludeTrashedVisible())->toBeTrue()
        ->and($ui->idleHintVisible())->toBeTrue();
});

it('theme getters return null before any override', function () {
    expect(Scoutify::theme()->getInput())->toBeNull()
        ->and(Scoutify::theme()->getDialogPanel())->toBeNull()
        ->and(Scoutify::theme()->getTrigger())->toBeNull();
});

it('theme setter is reflected in getter', function () {
    Scoutify::theme()
        ->input('custom-input-class')
        ->dialogPanel('custom-panel-class');

    expect(Scoutify::theme()->getInput())->toBe('custom-input-class')
        ->and(Scoutify::theme()->getDialogPanel())->toBe('custom-panel-class');
});

it('custom color registered on theme resolves to combined class string', function () {
    Scoutify::theme()->color('brand', 'bg-blue-100 text-blue-700', 'dark:bg-blue-900/60 dark:text-blue-200');

    expect(Scoutify::theme()->resolveColorClasses('brand'))
        ->toBe('bg-blue-100 text-blue-700 dark:bg-blue-900/60 dark:text-blue-200');
});

it('types registered via fluent API flow through SearchAggregator', function () {
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->timestamps();
    });

    Article::create(['name' => 'Fluent API Integration']);

    Scoutify::types()->register(Article::class, label: 'Articles', icon: 'heroicon-o-document', color: 'green');

    $groups = SearchAggregator::make()->search('Fluent');

    expect($groups)->toHaveCount(1)
        ->and($groups->first()->label)->toBe('Articles');
});
