<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Matheusmarnt\Scoutify\Services\SearchAggregator;
use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\FilteringArticle;

beforeEach(function () {
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('body')->nullable();
        $table->timestamps();
    });

    FilteringArticle::$hookCalled = false;
    FilteringArticle::$hookQuery = null;

    // Isolate: unbind registry so bootSearchable() skips registration and aggregator
    // uses only the explicit types array — prevents Article boot leaking into types.
    app()->offsetUnset(GlobalSearchRegistry::class);
});

// SearchAggregatorBuilderHookTest: hook is called with builder and query
it('calls globalSearchBuilder hook with correct query', function () {
    Article::create(['name' => 'Test Result']);

    $aggregator = new SearchAggregator([FilteringArticle::class => []]);
    $aggregator->search('Test');

    expect(FilteringArticle::$hookCalled)->toBeTrue()
        ->and(FilteringArticle::$hookQuery)->toBe('Test');
});

// Hook return value is used: where('id', 0) filters everything → empty group
it('uses builder returned by globalSearchBuilder hook', function () {
    Article::create(['name' => 'Test Result']);

    $aggregator = new SearchAggregator([FilteringArticle::class => []]);
    $groups = $aggregator->search('Test');

    expect($groups)->toBeEmpty();
});

// No regression: model without hook behaves identically to before
it('works without globalSearchBuilder hook (no regression)', function () {
    Article::create(['name' => 'Test Article']);

    $aggregator = new SearchAggregator([Article::class => []]);
    $groups = $aggregator->search('Test');

    expect($groups)->toHaveCount(1)
        ->and($groups->first()->results)->toHaveCount(1);
});
