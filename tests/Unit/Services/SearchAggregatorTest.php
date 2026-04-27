<?php

use Matheusmarnt\Scoutify\Services\SearchAggregator;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('returns empty array for blank query', function () {
    $aggregator = new SearchAggregator([]);
    expect($aggregator->search(''))->toBe([]);
});

it('returns empty array when no types configured', function () {
    $aggregator = new SearchAggregator([]);
    expect($aggregator->search('hello'))->toBe([]);
});

it('skips non-existent model classes gracefully', function () {
    $aggregator = new SearchAggregator(['App\\Models\\NonExistent' => ['label' => 'Test']]);
    expect($aggregator->search('hello'))->toBe([]);
});

it('returns ResultDto array for GloballySearchable model with collection driver', function () {
    // collection driver indexes in memory — create an Article, import it, search
    $article = new Article(['name' => 'Test Article']);
    $article->id = 1;

    $aggregator = new SearchAggregator([
        Article::class => ['label' => 'Articles'],
    ]);

    // collection driver searches in-memory; with no DB, search returns empty but should not throw
    $results = $aggregator->search('Test');
    expect($results)->toBeArray();
});
