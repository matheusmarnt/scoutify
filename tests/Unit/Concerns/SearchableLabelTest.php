<?php

use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('globalSearchLabel returns pluralized basename when no translation key exists', function () {
    // Article has no translation key registered, should return pluralized class basename
    $label = Article::globalSearchLabel();

    expect($label)->toBe('Articles');
});

it('globalSearchLabel returns pluralized basename for anonymous class with generic name', function () {
    $model = new class extends Model
    {
        use Searchable;
    };

    // Anonymous classes have a generated basename; just assert it's a non-empty string
    expect($model::globalSearchLabel())->toBeString()->not->toBeEmpty();
});

it('globalSearchLabel returns translation when key is registered in lang', function () {
    // Register a translation for the Article type
    app('translator')->addLines(
        ['scoutify.types.article_plural' => 'Documents'],
        'en',
        'scoutify'
    );

    $label = Article::globalSearchLabel();

    expect($label)->toBe('Documents');

    // Reset: re-check without the override by using a fresh key lookup
    // (the trans loader caches in-memory, so just assert the value is what we set)
});
