<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

beforeEach(function () {
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('body')->nullable();
        $table->timestamps();
    });
});

it('scoutify:flush succeeds with no types configured', function () {
    config(['scoutify.types' => []]);
    $this->artisan('scoutify:flush')
        ->assertSuccessful();
});

it('scoutify:flush flushes a specific model given as argument', function () {
    $this->artisan('scoutify:flush', ['model' => Article::class])
        ->assertSuccessful();
});

it('scoutify:flush iterates all configured types', function () {
    config(['scoutify.types' => [Article::class => []]]);
    $this->artisan('scoutify:flush')
        ->assertSuccessful();
});
