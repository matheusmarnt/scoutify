<?php

use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('falls back to Str::plural of class basename when no translation key is registered', function () {
    // No translation key for "article_plural" is registered by default.
    // The lang files ship with the types array empty (all examples commented out).
    // This documents the expected fallback: English pluralization via Str::plural().
    $label = Article::globalSearchLabel();

    expect($label)->toBe('Articles');
});

it('fallback returns a non-empty string (English-only behavior via Str::plural)', function () {
    // Documents that the fallback is always English regardless of active locale,
    // because Str::plural() does not translate — it only pluralizes English words.
    $model = new class extends Model
    {
        use Searchable;
    };

    $label = $model::globalSearchLabel();

    expect($label)->toBeString()->not->toBeEmpty();
});
