<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Matheusmarnt\Scoutify\Services\SearchAggregator;
use Matheusmarnt\Scoutify\Support\GlobalSearchGroup;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

beforeEach(function () {
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('body')->nullable();
        $table->timestamps();
    });
});

it('returns a collection of GlobalSearchGroup', function () {
    Article::create(['name' => 'Laravel Article']);

    $aggregator = new SearchAggregator([Article::class => ['label' => 'Articles']]);
    $groups = $aggregator->search('Laravel');

    expect($groups)->toBeInstanceOf(Collection::class)
        ->and($groups->first())->toBeInstanceOf(GlobalSearchGroup::class);
});

it('groups results with correct metadata', function () {
    Article::create(['name' => 'Test Article']);

    $aggregator = new SearchAggregator([Article::class => ['label' => 'My Articles', 'icon' => 'heroicon-o-document', 'color' => 'blue']]);
    $groups = $aggregator->search('Test');
    $group = $groups->first();

    expect($group->label)->toBe('My Articles')
        ->and($group->color)->toBe('blue')
        ->and($group->total)->toBe(1)
        ->and($group->results)->toHaveCount(1);
});

it('returns empty collection for blank query', function () {
    $aggregator = new SearchAggregator([Article::class => ['label' => 'Articles']]);
    $groups = $aggregator->search('');

    expect($groups)->toBeInstanceOf(Collection::class)
        ->and($groups)->toBeEmpty();
});

it('does not include empty groups', function () {
    $aggregator = new SearchAggregator([Article::class => ['label' => 'Articles']]);
    $groups = $aggregator->search('NonExistent');

    expect($groups)->toBeEmpty();
});

it('config metadata overrides registry defaults', function () {
    Article::create(['name' => 'Test Article']);

    config(['scoutify.types' => [
        Article::class => ['label' => 'Config Label', 'icon' => 'heroicon-o-star', 'color' => 'red'],
    ]]);

    $aggregator = SearchAggregator::make();
    $groups = $aggregator->search('Test');

    $group = $groups->first();
    expect($group->label)->toBe('Config Label')
        ->and($group->color)->toBe('red');
});
