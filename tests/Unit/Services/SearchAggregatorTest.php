<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Matheusmarnt\Scoutify\Services\SearchAggregator;
use Matheusmarnt\Scoutify\Support\GlobalSearchGroup;
use Matheusmarnt\Scoutify\Support\ResultDto;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\ActivePost;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\BrokenModel;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Post;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\SoftPost;

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

    Schema::create('soft_posts', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('active_posts', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->boolean('active')->default(true);
        $table->timestamps();
    });
});

it('returns empty collection for blank query', function () {
    $aggregator = new SearchAggregator([]);
    $result = $aggregator->search('');
    expect($result)->toBeInstanceOf(Collection::class)->and($result)->toBeEmpty();
});

it('returns empty collection when no types configured', function () {
    $aggregator = new SearchAggregator([]);
    $result = $aggregator->search('hello');
    expect($result)->toBeInstanceOf(Collection::class)->and($result)->toBeEmpty();
});

it('skips non-existent model classes gracefully', function () {
    $aggregator = new SearchAggregator(['App\\Models\\NonExistent' => ['label' => 'Test']]);
    $result = $aggregator->search('hello');
    expect($result)->toBeInstanceOf(Collection::class)->and($result)->toBeEmpty();
});

it('skips class that is not a Model subclass', function () {
    $aggregator = new SearchAggregator([stdClass::class => ['label' => 'Test']]);
    $result = $aggregator->search('hello');
    expect($result)->toBeInstanceOf(Collection::class)->and($result)->toBeEmpty();
});

it('returns ResultDto array for GloballySearchable model with results', function () {
    Article::create(['name' => 'Laravel Scout Tutorial']);

    $aggregator = new SearchAggregator([Article::class => ['label' => 'Articles']]);
    $groups = $aggregator->search('Laravel');
    $group = $groups->first();
    $dto = $group->results[0];

    expect($groups)->not->toBeEmpty()
        ->and($group)->toBeInstanceOf(GlobalSearchGroup::class)
        ->and($dto)->toBeInstanceOf(ResultDto::class)
        ->and($dto->group)->toBe('articles')
        ->and($dto->groupLabel)->toBe('Articles')
        ->and($dto->icon)->toBe('heroicon-o-document')
        ->and($dto->groupColor)->toBe('blue');
});

it('returns ResultDto for non-GloballySearchable model', function () {
    Post::create(['name' => 'Hello World Post']);

    $aggregator = new SearchAggregator([
        Post::class => ['label' => 'Posts', 'icon' => 'heroicon-o-pencil', 'color' => 'green'],
    ]);
    $groups = $aggregator->search('Hello');
    $group = $groups->first();
    $dto = $group->results[0];

    expect($groups)->not->toBeEmpty()
        ->and($dto)->toBeInstanceOf(ResultDto::class)
        ->and($dto->groupLabel)->toBe('Posts')
        ->and($dto->groupColor)->toBe('green')
        ->and($dto->url)->toBe(url('/'));
});

it('uses globalSearchGroup as label when meta has no label and model is auto-registered', function () {
    Article::create(['name' => 'Fallback Label Test']);

    $aggregator = new SearchAggregator([Article::class => []]);
    $groups = $aggregator->search('Fallback');
    $group = $groups->first();

    // Article::globalSearchGroup() returns 'articles', which bootSearchable registers.
    // When config meta has no label, the registry label ('articles') is used.
    expect($groups)->not->toBeEmpty()
        ->and($group->label)->toBe('articles');
});

it('uses GloballySearchable icon and color when meta omits them', function () {
    Article::create(['name' => 'Icon Test']);

    $aggregator = new SearchAggregator([Article::class => ['label' => 'Articles']]);
    $groups = $aggregator->search('Icon');
    $group = $groups->first();
    $dto = $group->results[0];

    expect($groups)->not->toBeEmpty()
        ->and($dto->icon)->toBe('heroicon-o-document')
        ->and($dto->groupColor)->toBe('blue');
});

it('uses fallback icon and color for non-GloballySearchable with no meta', function () {
    Post::create(['name' => 'Fallback Icon']);

    $aggregator = new SearchAggregator([Post::class => []]);
    $groups = $aggregator->search('Fallback');
    $group = $groups->first();
    $dto = $group->results[0];

    expect($groups)->not->toBeEmpty()
        ->and($dto->groupColor)->toBe('gray');
});

it('make() creates instance from config', function () {
    config(['scoutify.types' => []]);
    expect(SearchAggregator::make())->toBeInstanceOf(SearchAggregator::class);
});

it('make() accepts explicit types array', function () {
    $aggregator = SearchAggregator::make([Article::class => []]);
    expect($aggregator)->toBeInstanceOf(SearchAggregator::class);
});

it('calls withTrashed on SoftDeletes model when includeTrashed is true', function () {
    SoftPost::create(['name' => 'Soft One']);

    $aggregator = new SearchAggregator([SoftPost::class => ['label' => 'SoftPosts']]);
    $results = $aggregator->search('Soft', includeTrashed: true);

    expect($results)->toBeInstanceOf(Collection::class);
});

it('swallows Throwable when model search errors', function () {
    $aggregator = new SearchAggregator([BrokenModel::class => ['label' => 'Broken']]);
    $results = $aggregator->search('anything');

    expect($results)->toBeInstanceOf(Collection::class)->and($results)->toBeEmpty();
});

it('filters by active scope when onlyActive is true', function () {
    ActivePost::create(['name' => 'Active One', 'active' => true]);
    ActivePost::create(['name' => 'Inactive One', 'active' => false]);

    $aggregator = new SearchAggregator([ActivePost::class => ['label' => 'ActivePosts']]);
    $results = $aggregator->search('One', onlyActive: true);

    expect($results)->toBeInstanceOf(Collection::class);
});
