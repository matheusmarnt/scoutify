<?php

use Matheusmarnt\Scoutify\Services\ScoutConfigurator;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('detects model already using Searchable trait', function () {
    // Article fixture uses Matheusmarnt\Scoutify\Concerns\Searchable
    expect(ScoutConfigurator::isAlreadySearchable(Article::class))->toBeTrue();
});

it('returns false for non-existent class', function () {
    expect(ScoutConfigurator::isAlreadySearchable('App\\Models\\NonExistent'))->toBeFalse();
});

it('resolves file path for existing class', function () {
    $path = ScoutConfigurator::resolveFilePath(Article::class);
    expect($path)->toBeString()->toEndWith('.php');
});
