<?php

use Matheusmarnt\Scoutify\Services\SearchAggregator;

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
