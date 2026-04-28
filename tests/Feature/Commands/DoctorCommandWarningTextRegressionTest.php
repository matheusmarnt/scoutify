<?php

use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('warns with registry-aware message when no types are discovered', function () {
    config(['scout.driver' => 'database', 'scoutify.types' => []]);

    app()->offsetUnset(GlobalSearchRegistry::class);
    app()->singleton(GlobalSearchRegistry::class);

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('config + registry both empty');
});

it('warning message does not solely reference scoutify.types config key', function () {
    config(['scout.driver' => 'database', 'scoutify.types' => []]);

    app()->offsetUnset(GlobalSearchRegistry::class);
    app()->singleton(GlobalSearchRegistry::class);

    $this->artisan('scoutify:doctor')
        ->doesntExpectOutputToContain('No types configured in scoutify.types');
});

it('no warning when registry has types', function () {
    config(['scout.driver' => 'database', 'scoutify.types' => []]);

    $registry = app(GlobalSearchRegistry::class);
    $registry->register(Article::class, [
        'key' => 'article',
        'label' => 'Articles',
        'icon' => 'heroicon-o-document',
        'color' => 'blue',
    ]);

    $this->artisan('scoutify:doctor')
        ->doesntExpectOutputToContain('config + registry both empty');
});
