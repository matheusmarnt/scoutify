<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

beforeEach(function () {
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('body')->nullable();
        $table->timestamps();
    });
});

it('renders group-header for each type in results', function () {
    Article::create(['name' => 'Scout Article']);

    config(['scoutify.types' => [Article::class => [
        'label' => 'Articles',
        'icon' => 'heroicon-o-document',
        'color' => 'blue',
    ]]]);

    Livewire::test(Modal::class)
        ->set('query', 'Scout')
        ->assertSeeHtml('Articles');
});

it('renders result rows within groups', function () {
    Article::create(['name' => 'Grouped Result']);

    config(['scoutify.types' => [Article::class => [
        'label' => 'Articles',
        'icon' => 'heroicon-o-document',
        'color' => 'blue',
    ]]]);

    Livewire::test(Modal::class)
        ->set('query', 'Grouped')
        ->assertSeeHtml('<mark class="scoutify-mark">Grouped</mark>');
});

it('shows empty-state when query returns no results', function () {
    config(['scoutify.types' => [Article::class => [
        'label' => 'Articles',
        'icon' => 'heroicon-o-document',
        'color' => 'blue',
    ]]]);

    Livewire::test(Modal::class)
        ->set('query', 'xyznonexistent123')
        ->assertSee('No results');
});
