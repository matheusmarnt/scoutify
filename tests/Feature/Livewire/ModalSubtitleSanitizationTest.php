<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;
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

it('strips html tags from body field and highlights plain text in subtitle', function () {
    Article::create(['name' => 'Tutorial', 'body' => '<p>Hello <strong>world</strong></p>']);

    app(ScoutifyManager::class)->types()->register(Article::class,
        label: 'Articles', icon: 'heroicon-o-document', color: 'blue'
    );

    Livewire::test(Modal::class)
        ->set('query', 'world')
        ->assertSeeHtml('Hello <mark class="scoutify-mark">world</mark>')
        ->assertDontSeeHtml('&lt;p&gt;')
        ->assertDontSeeHtml('&lt;strong&gt;');
});

it('does not render subtitle when body contains only script tags', function () {
    Article::create(['name' => 'Article', 'body' => '<script>alert(1)</script>']);

    app(ScoutifyManager::class)->types()->register(Article::class,
        label: 'Articles', icon: 'heroicon-o-document', color: 'blue'
    );

    Livewire::test(Modal::class)
        ->set('query', 'Article')
        ->assertDontSeeHtml('alert(1)');
});

it('decodes html entities in subtitle without double-encoding', function () {
    Article::create(['name' => 'Bread butter', 'body' => '<p>bread &amp; butter</p>']);

    app(ScoutifyManager::class)->types()->register(Article::class,
        label: 'Articles', icon: 'heroicon-o-document', color: 'blue'
    );

    Livewire::test(Modal::class)
        ->set('query', 'Bread butter')
        ->assertSeeHtml('bread &amp; butter')
        ->assertDontSeeHtml('&amp;amp;');
});
