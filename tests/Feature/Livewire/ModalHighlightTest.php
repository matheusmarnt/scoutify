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

it('renders mark tags around the matched query term in results', function () {
    Article::create(['name' => 'Laravel Scout Tutorial']);

    config(['scoutify.types' => [Article::class => [
        'label' => 'Articles',
        'icon' => 'heroicon-o-document',
        'color' => 'blue',
    ]]]);

    Livewire::test(Modal::class)
        ->set('query', 'Laravel')
        ->assertSeeHtml('<mark class="scoutify-mark">Laravel</mark>');
});
