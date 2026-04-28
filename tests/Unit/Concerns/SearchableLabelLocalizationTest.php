<?php

use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('returns translation from lang file when key exists for active locale', function () {
    app('translator')->addLines(
        ['scoutify.types.article_plural' => 'Articles EN'],
        'en',
        'scoutify'
    );

    $label = Article::globalSearchLabel();

    expect($label)->toBe('Articles EN');
});

it('returns pt_BR translation when locale is set to pt_BR and key exists', function () {
    \Illuminate\Support\Facades\App::setLocale('pt_BR');

    app('translator')->addLines(
        ['scoutify.types.article_plural' => 'Artigos'],
        'pt_BR',
        'scoutify'
    );

    $label = Article::globalSearchLabel();

    expect($label)->toBe('Artigos');

    \Illuminate\Support\Facades\App::setLocale('en');
});
