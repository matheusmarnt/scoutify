<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\AlreadyRegistered;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\PartiallyRegistered;

beforeEach(function () {
    if (! class_exists('App\Filament\Resources\ArticleResource')) {
        require __DIR__.'/../../Fixtures/Filament/ArticleResource.php';
    }
    if (! class_exists('App\Filament\Resources\PartiallyRegisteredResource')) {
        require __DIR__.'/../../Fixtures/Filament/PartiallyRegisteredResource.php';
    }
});

it('returns globallySearchableArray with title subtitle and url', function () {
    $article = new Article(['name' => 'Hello World']);

    $result = $article->toGloballySearchableArray();

    expect($result)->toHaveKeys(['title', 'subtitle', 'url'])
        ->and($result['title'])->toBe('Hello World')
        ->and($result['subtitle'])->toBeNull();
});

it('uses name attribute as default title', function () {
    $article = new Article(['name' => 'My Article']);

    expect($article->globalSearchTitle())->toBe('My Article');
});

it('returns null subtitle by default', function () {
    $article = new Article(['name' => 'My Article']);

    expect($article->globalSearchSubtitle())->toBeNull();
});

it('implements GloballySearchable contract', function () {
    expect(Article::globalSearchGroup())->toBe('articles')
        ->and(Article::globalSearchIcon())->toBe('heroicon-o-document')
        ->and(Article::globalSearchColor())->toBe('blue');
});

it('provides default group, icon and color from class basename', function () {
    expect(Article::globalSearchGroup())->toBe('articles')
        ->and(Article::globalSearchIcon())->toBe('heroicon-o-document')
        ->and(Article::globalSearchColor())->toBe('blue');
});

it('searchable trait provides defaults when not overridden', function () {
    $model = new class extends Model
    {
        use Searchable;
    };

    expect(strlen($model::globalSearchGroup()))->toBeGreaterThan(0)
        ->and($model::globalSearchIcon())->toBe('heroicon-o-magnifying-glass')
        ->and($model::globalSearchColor())->toBe('gray');
});

it('globalSearchUrl resolves filament resource url', function () {
    $article = new Article;
    $article->id = 42;

    expect($article->globalSearchUrl())->toBe('/articles/42');
});

it('globalSearchUrl skips filament resource that throws and falls back', function () {
    $model = new PartiallyRegistered;

    expect($model->globalSearchUrl())->toBe(url('/'));
});

it('globalSearchUrl falls back to named route when no filament resource', function () {
    Route::get('/already-registereds/{model}', ['as' => 'already_registereds.show', 'uses' => fn () => '']);

    $model = new AlreadyRegistered;
    $model->id = 7;

    expect($model->globalSearchUrl())->toContain('/already-registereds/7');
});
