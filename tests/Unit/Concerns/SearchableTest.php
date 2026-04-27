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
