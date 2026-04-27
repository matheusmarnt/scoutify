<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Matheusmarnt\Scoutify\Services\SearchAggregator;
use Matheusmarnt\Scoutify\Support\ResultDto;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Post;

beforeEach(function () {
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('body')->nullable();
        $table->timestamps();
    });

    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('title')->nullable();
        $table->timestamps();
    });
});

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

it('skips class that is not a Model subclass', function () {
    $aggregator = new SearchAggregator([\stdClass::class => ['label' => 'Test']]);
    expect($aggregator->search('hello'))->toBe([]);
});

it('returns ResultDto array for GloballySearchable model with results', function () {
    Article::create(['name' => 'Laravel Scout Tutorial']);

    $aggregator = new SearchAggregator([Article::class => ['label' => 'Articles']]);
    $results = $aggregator->search('Laravel');

    expect($results)->not->toBeEmpty()
        ->and($results[0])->toBeInstanceOf(ResultDto::class)
        ->and($results[0]->group)->toBe('articles')
        ->and($results[0]->groupLabel)->toBe('Articles')
        ->and($results[0]->icon)->toBe('heroicon-o-document')
        ->and($results[0]->groupColor)->toBe('blue');
});

it('returns ResultDto for non-GloballySearchable model', function () {
    Post::create(['name' => 'Hello World Post']);

    $aggregator = new SearchAggregator([
        Post::class => ['label' => 'Posts', 'icon' => 'heroicon-o-pencil', 'color' => 'green'],
    ]);
    $results = $aggregator->search('Hello');

    expect($results)->not->toBeEmpty()
        ->and($results[0])->toBeInstanceOf(ResultDto::class)
        ->and($results[0]->groupLabel)->toBe('Posts')
        ->and($results[0]->groupColor)->toBe('green')
        ->and($results[0]->url)->toBe(url('/'));
});

it('uses class_basename as label when meta has no label', function () {
    Article::create(['name' => 'Fallback Label Test']);

    $aggregator = new SearchAggregator([Article::class => []]);
    $results = $aggregator->search('Fallback');

    expect($results)->not->toBeEmpty()
        ->and($results[0]->groupLabel)->toBe('Article');
});

it('uses GloballySearchable icon and color when meta omits them', function () {
    Article::create(['name' => 'Icon Test']);

    $aggregator = new SearchAggregator([Article::class => ['label' => 'Articles']]);
    $results = $aggregator->search('Icon');

    expect($results)->not->toBeEmpty()
        ->and($results[0]->icon)->toBe('heroicon-o-document')
        ->and($results[0]->groupColor)->toBe('blue');
});

it('uses fallback icon and color for non-GloballySearchable with no meta', function () {
    Post::create(['name' => 'Fallback Icon']);

    $aggregator = new SearchAggregator([Post::class => []]);
    $results = $aggregator->search('Fallback');

    expect($results)->not->toBeEmpty()
        ->and($results[0]->groupColor)->toBe('gray');
});

it('make() creates instance from config', function () {
    config(['scoutify.types' => []]);
    expect(SearchAggregator::make())->toBeInstanceOf(SearchAggregator::class);
});

it('make() accepts explicit types array', function () {
    $aggregator = SearchAggregator::make([Article::class => []]);
    expect($aggregator)->toBeInstanceOf(SearchAggregator::class);
});
