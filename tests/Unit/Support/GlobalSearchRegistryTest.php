<?php

use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('registers and retrieves a model class', function () {
    $registry = new GlobalSearchRegistry;
    $registry->register(Article::class, ['key' => 'articles', 'label' => 'Articles', 'icon' => 'icon', 'color' => 'blue']);

    expect($registry->get(Article::class))->toMatchArray(['key' => 'articles']);
});

it('returns null for unregistered class', function () {
    $registry = new GlobalSearchRegistry;

    expect($registry->get('NonExistent'))->toBeNull();
});

it('returns all registered entries', function () {
    $registry = new GlobalSearchRegistry;
    $registry->register(Article::class, ['key' => 'articles', 'label' => 'Articles', 'icon' => 'i', 'color' => 'blue']);

    expect($registry->all())->toHaveKey(Article::class);
});
