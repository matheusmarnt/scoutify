<?php

use Illuminate\Support\Facades\App;
use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

afterEach(function () {
    App::setLocale('en');
});

it('availableTypes returns locale-aware label when locale is pt_BR and translation is registered', function () {
    config()->set('scoutify.types', []);

    // 1. Register the model at en locale (default) — no label stored, resolved dynamically later.
    app(GlobalSearchRegistry::class)->register(Article::class, [
        'key' => Article::globalSearchGroup(),
        'icon' => Article::globalSearchIcon(),
        'color' => Article::globalSearchColor(),
    ]);

    // 2. Change locale to pt_BR and register the translation.
    App::setLocale('pt_BR');
    app('translator')->addLines(
        ['scoutify.types.article_plural' => 'Artigos PT'],
        'pt_BR',
        'scoutify'
    );

    // 3. Call availableTypes() — label must be resolved dynamically at request time.
    $component = Livewire::test(Modal::class);
    $types = $component->instance()->availableTypes();

    expect($types)
        ->toHaveCount(1)
        ->and($types[0]['label'])->toBe('Artigos PT');
});

it('availableTypes returns English label when locale is en and translation is registered for en', function () {
    config()->set('scoutify.types', []);

    // 1. Register the model without a label (dynamic resolution).
    app(GlobalSearchRegistry::class)->register(Article::class, [
        'key' => Article::globalSearchGroup(),
        'icon' => Article::globalSearchIcon(),
        'color' => Article::globalSearchColor(),
    ]);

    // 2. Register the en translation (locale stays en).
    app('translator')->addLines(
        ['scoutify.types.article_plural' => 'Articles EN'],
        'en',
        'scoutify'
    );

    // 3. Call availableTypes() — label resolved at request time in en locale.
    $component = Livewire::test(Modal::class);
    $types = $component->instance()->availableTypes();

    expect($types)
        ->toHaveCount(1)
        ->and($types[0]['label'])->toBe('Articles EN');
});
