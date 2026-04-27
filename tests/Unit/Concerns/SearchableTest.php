<?php

use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

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
    $model = new class extends \Illuminate\Database\Eloquent\Model {
        use \Matheusmarnt\Scoutify\Concerns\Searchable;
    };

    expect(strlen($model::globalSearchGroup()))->toBeGreaterThan(0)
        ->and($model::globalSearchIcon())->toBe('heroicon-o-magnifying-glass')
        ->and($model::globalSearchColor())->toBe('gray');
});
