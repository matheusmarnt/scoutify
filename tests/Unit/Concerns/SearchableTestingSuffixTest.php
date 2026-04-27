<?php

use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('appends _testing to searchableAs in test environment', function () {
    expect(app()->environment())->toBe('testing');
    $article = new Article;
    expect($article->searchableAs())->toEndWith('_testing');
});

it('returns the correct full index name in testing', function () {
    $article = new Article;
    expect($article->searchableAs())->toBe('articles_testing');
});
