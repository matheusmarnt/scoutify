<?php

use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('bootSearchable payload includes label key from globalSearchLabel', function () {
    $payload = [
        'key' => Article::globalSearchGroup(),
        'label' => Article::globalSearchLabel(),
        'icon' => Article::globalSearchIcon(),
        'color' => Article::globalSearchColor(),
    ];

    expect($payload)->toHaveKey('label')
        ->and($payload['label'])->toBe('Articles')
        ->and($payload['label'])->not->toBe($payload['key']);
});

it('globalSearchLabel is distinct from globalSearchGroup', function () {
    expect(Article::globalSearchLabel())->not->toBe(Article::globalSearchGroup());
});

it('registry entry contains label when populated via bootSearchable payload', function () {
    app()->offsetUnset(GlobalSearchRegistry::class);
    app()->singleton(GlobalSearchRegistry::class);

    $registry = app(GlobalSearchRegistry::class);
    $registry->register(Article::class, [
        'key' => Article::globalSearchGroup(),
        'label' => Article::globalSearchLabel(),
        'icon' => Article::globalSearchIcon(),
        'color' => Article::globalSearchColor(),
    ]);

    $types = $registry->all();

    expect($types[Article::class])->toHaveKey('label')
        ->and($types[Article::class]['label'])->toBe('Articles');
});
