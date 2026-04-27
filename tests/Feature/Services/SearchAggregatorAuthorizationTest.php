<?php

use Illuminate\Auth\GenericUser;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Matheusmarnt\Scoutify\Services\SearchAggregator;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

beforeEach(function () {
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('body')->nullable();
        $table->timestamps();
    });

    $this->actingAs(new GenericUser(['id' => 1]));
});

it('excludes records denied by view Gate', function () {
    $allowed = Article::create(['name' => 'Public Article']);
    $denied = Article::create(['name' => 'Private Article']);

    Gate::define('view', fn ($user, $record) => $record->getKey() !== $denied->getKey());

    $aggregator = new SearchAggregator([Article::class => ['label' => 'Articles']]);
    $results = $aggregator->search('Article');

    $keys = collect($results)->pluck('modelKey')->all();
    expect($keys)->toContain((string) $allowed->getKey())
        ->and($keys)->not->toContain((string) $denied->getKey());
});

it('includes records allowed by view Gate', function () {
    $allowed = Article::create(['name' => 'Allowed Article']);

    Gate::define('view', fn () => true);

    $aggregator = new SearchAggregator([Article::class => ['label' => 'Articles']]);
    $results = $aggregator->search('Allowed');

    $keys = collect($results)->pluck('modelKey')->all();
    expect($keys)->toContain((string) $allowed->getKey());
});

it('includes records when no view Gate is defined', function () {
    $article = Article::create(['name' => 'Open Article']);

    $aggregator = new SearchAggregator([Article::class => ['label' => 'Articles']]);
    $results = $aggregator->search('Open');

    expect($results)->not->toBeEmpty();
});
