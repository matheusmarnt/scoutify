<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Matheusmarnt\Scoutify\Services\SearchAggregator;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\ExternalPreviewArticle;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\PreviewableArticle;

beforeEach(function () {
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('body')->nullable();
        $table->boolean('is_published')->default(false);
        $table->timestamps();
    });

    Schema::create('previewable_articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('file_disk')->nullable();
        $table->string('file_path')->nullable();
        $table->timestamps();
    });

    config(['scoutify.authorization.default' => 'permissive']);
});

it('populates preview in ResultDto for models implementing HasGlobalSearchPreview', function () {
    PreviewableArticle::create([
        'name' => 'Preview Report',
        'file_disk' => 'local',
        'file_path' => 'reports/q4.pdf',
    ]);

    $aggregator = new SearchAggregator([PreviewableArticle::class => ['label' => 'Articles']]);
    $results = $aggregator->search('Preview');

    $resultArrays = collect($results)->flatMap(fn ($g) => $g->results)->map->toArray()->all();

    expect($resultArrays)->not->toBeEmpty()
        ->and($resultArrays[0]['preview'])->not->toBeNull()
        ->and($resultArrays[0]['preview']['disk'])->toBe('local')
        ->and($resultArrays[0]['preview']['path'])->toBe('reports/q4.pdf')
        ->and($resultArrays[0]['modelClass'])->toBe(PreviewableArticle::class);
});

it('sets preview null for models without HasGlobalSearchPreview', function () {
    Article::create(['name' => 'Plain Article']);

    $aggregator = new SearchAggregator([Article::class => ['label' => 'Articles']]);
    $results = $aggregator->search('Plain');

    $resultArrays = collect($results)->flatMap(fn ($g) => $g->results)->map->toArray()->all();

    expect($resultArrays)->not->toBeEmpty()
        ->and($resultArrays[0]['preview'])->toBeNull();
});

it('sets preview null when model returns null from globalSearchPreview', function () {
    PreviewableArticle::create(['name' => 'No File Article']);

    $aggregator = new SearchAggregator([PreviewableArticle::class => ['label' => 'Articles']]);
    $results = $aggregator->search('No File');

    $resultArrays = collect($results)->flatMap(fn ($g) => $g->results)->map->toArray()->all();

    expect($resultArrays)->not->toBeEmpty()
        ->and($resultArrays[0]['preview'])->toBeNull();
});

it('supports external URL preview', function () {
    ExternalPreviewArticle::create(['name' => 'External Report']);

    $aggregator = new SearchAggregator([ExternalPreviewArticle::class => ['label' => 'Articles']]);
    $results = $aggregator->search('External');

    $resultArrays = collect($results)->flatMap(fn ($g) => $g->results)->map->toArray()->all();

    expect($resultArrays)->not->toBeEmpty()
        ->and($resultArrays[0]['preview']['url'])->toBe('https://cdn.example.com/report.pdf')
        ->and($resultArrays[0]['preview']['disk'])->toBeNull();
});
