<?php

use Illuminate\Support\Facades\App;
use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

afterEach(function () {
    App::setLocale('en');
});

it('mounted Modal availableTypes property contains Portuguese label when APP_LOCALE is pt_BR', function () {
    config()->set('scoutify.types', []);

    // 1. Register at en locale — no label baked in, resolved dynamically at read time.
    app(GlobalSearchRegistry::class)->register(Article::class, [
        'key' => Article::globalSearchGroup(),
        'icon' => Article::globalSearchIcon(),
        'color' => Article::globalSearchColor(),
    ]);

    // 2. Change locale to pt_BR and register the translation.
    App::setLocale('pt_BR');
    app('translator')->addLines(
        ['scoutify.types.article_plural' => 'Artigos'],
        'pt_BR',
        'scoutify'
    );

    // 3. availableTypes() must call globalSearchLabel() dynamically and return 'Artigos'.
    $component = Livewire::test(Modal::class);
    $availableTypes = $component->instance()->availableTypes();
    $labels = array_column($availableTypes, 'label');

    expect($labels)->toContain('Artigos');
});

it('mounted Modal availableTypes label is NOT English fallback when pt_BR translation is active', function () {
    config()->set('scoutify.types', []);

    // 1. Register at en locale — no label baked in.
    app(GlobalSearchRegistry::class)->register(Article::class, [
        'key' => Article::globalSearchGroup(),
        'icon' => Article::globalSearchIcon(),
        'color' => Article::globalSearchColor(),
    ]);

    // 2. Change locale to pt_BR and register the translation.
    App::setLocale('pt_BR');
    app('translator')->addLines(
        ['scoutify.types.article_plural' => 'Artigos'],
        'pt_BR',
        'scoutify'
    );

    // 3. With dynamic resolution, label must be 'Artigos', not the English fallback 'Articles'.
    $component = Livewire::test(Modal::class);
    $availableTypes = $component->instance()->availableTypes();
    $labels = array_column($availableTypes, 'label');

    expect($labels)->not->toContain('Articles')
        ->and($labels)->toContain('Artigos');
});
