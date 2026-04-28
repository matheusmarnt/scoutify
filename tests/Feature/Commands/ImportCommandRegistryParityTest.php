<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

beforeEach(function () {
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('body')->nullable();
        $table->timestamps();
    });

    config(['scoutify.types' => []]);
});

it('scoutify:import imports registry types even when config types is empty', function () {
    $registry = app(GlobalSearchRegistry::class);
    $registry->register(Article::class, [
        'key' => 'article',
        'label' => 'Articles',
        'icon' => 'heroicon-o-document',
        'color' => 'blue',
    ]);

    $this->artisan('scoutify:import')
        ->doesntExpectOutputToContain('No types')
        ->assertSuccessful();
});

it('scoutify:import warns when neither config nor registry has types', function () {
    app()->offsetUnset(GlobalSearchRegistry::class);
    app()->singleton(GlobalSearchRegistry::class);

    $this->artisan('scoutify:import')
        ->expectsOutputToContain('No types')
        ->assertSuccessful();
});
