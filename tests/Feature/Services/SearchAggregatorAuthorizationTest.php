<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Matheusmarnt\Scoutify\Services\SearchAggregator;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\FlagArticle;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\GatedArticle;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\PublicArticle;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\User;

beforeEach(function () {
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->timestamps();
    });

    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('body')->nullable();
        $table->boolean('is_published')->default(false);
        $table->timestamps();
    });

    config(['scoutify.authorization.default' => 'secure']);

    $user = User::create(['id' => 1, 'name' => 'Test User']);
    $this->actingAs($user);
});

it('excludes records denied by view Gate', function () {
    $allowed = Article::create(['name' => 'Public Article']);
    $denied = Article::create(['name' => 'Private Article']);

    Gate::define('view', fn ($user, $record) => $record->getKey() !== $denied->getKey());

    $aggregator = new SearchAggregator([Article::class => ['label' => 'Articles']]);
    $results = $aggregator->search('Article');

    $keys = collect($results)->flatMap(fn ($g) => collect($g->results)->pluck('modelKey'))->all();
    expect($keys)->toContain((string) $allowed->getKey())
        ->and($keys)->not->toContain((string) $denied->getKey());
});

it('includes records allowed by view Gate', function () {
    $allowed = Article::create(['name' => 'Allowed Article']);

    Gate::define('view', fn () => true);

    $aggregator = new SearchAggregator([Article::class => ['label' => 'Articles']]);
    $results = $aggregator->search('Allowed');

    $keys = collect($results)->flatMap(fn ($g) => collect($g->results)->pluck('modelKey'))->all();
    expect($keys)->toContain((string) $allowed->getKey());
});

it('includes records when no view Gate is defined', function () {
    $article = Article::create(['name' => 'Open Article']);

    $aggregator = new SearchAggregator([Article::class => ['label' => 'Articles']]);
    $results = $aggregator->search('Open');

    expect($results)->not->toBeEmpty();
});

it('allows guests to see models with visibleToGuests', function () {
    PublicArticle::create(['name' => 'Public Visibility']);

    auth()->logout();

    $aggregator = new SearchAggregator([PublicArticle::class => ['label' => 'Public']]);
    $results = $aggregator->search('Public');

    expect($results)->not->toBeEmpty();
});

it('denies guests from seeing models without visibleToGuests', function () {
    Article::create(['name' => 'Private to Guests']);

    auth()->logout();

    $aggregator = new SearchAggregator([Article::class => ['label' => 'Private']]);
    $results = $aggregator->search('Private');

    expect($results)->toBeEmpty();
});

it('gates results by Spatie permission', function () {
    GatedArticle::create(['name' => 'Gated Article']);

    // Create a custom user class that has hasPermissionTo
    $user = new class(['id' => 10]) extends User
    {
        public function hasPermissionTo($permission, $guard = null)
        {
            return $permission === 'view-gated-articles';
        }
    };

    $this->actingAs($user);

    $aggregator = new SearchAggregator([GatedArticle::class => ['label' => 'Gated']]);
    $results = $aggregator->search('Gated');

    expect($results)->not->toBeEmpty();

    // Now test with denied user
    $deniedUser = new class(['id' => 20]) extends User
    {
        public function hasPermissionTo($permission, $guard = null)
        {
            return false;
        }
    };

    $this->actingAs($deniedUser);
    $results = $aggregator->search('Gated');
    expect($results)->toBeEmpty();
});

it('filters results by boolean attribute', function () {
    $published = FlagArticle::create(['name' => 'Published', 'is_published' => true]);
    $draft = FlagArticle::create(['name' => 'Draft', 'is_published' => false]);

    $aggregator = new SearchAggregator([FlagArticle::class => ['label' => 'Flags']]);
    $results = $aggregator->search('Article'); // wait, names are Published/Draft

    $results = $aggregator->search('ished'); // matches Published
    expect($results)->not->toBeEmpty();

    $results = $aggregator->search('Draft');
    expect($results)->toBeEmpty();
});
