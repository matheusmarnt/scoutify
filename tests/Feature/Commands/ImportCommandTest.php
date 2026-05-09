<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Matheusmarnt\Scoutify\ScoutifyManager;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

beforeEach(function () {
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('body')->nullable();
        $table->timestamps();
    });
});

it('scoutify:import succeeds with no types configured', function () {
    $this->artisan('scoutify:import')
        ->assertSuccessful();
});

it('scoutify:import imports a specific model given as argument', function () {
    $this->artisan('scoutify:import', ['model' => Article::class])
        ->assertSuccessful();
});

it('scoutify:import iterates all types via fluent API', function () {
    app(ScoutifyManager::class)->types()->register(Article::class);
    $this->artisan('scoutify:import')
        ->assertSuccessful();
});
