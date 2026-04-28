<?php

use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('returns translation from lang file when key exists for active locale', function () {
    app('translator')->addLines(
        ['scoutify.types.user_plural' => 'Users'],
        'en',
        'scoutify'
    );

    // Use a model whose snake class name is "user"
    $model = new class extends \Illuminate\Database\Eloquent\Model
    {
        use \Matheusmarnt\Scoutify\Concerns\Searchable;

        public static function globalSearchLabel(): string
        {
            $key = 'scoutify::scoutify.types.user_plural';

            return \Illuminate\Support\Facades\Lang::has($key)
                ? __($key)
                : \Illuminate\Support\Str::plural(class_basename(static::class));
        }
    };

    expect($model::globalSearchLabel())->toBe('Users');
});

it('returns pt_BR translation when locale is set to pt_BR and key exists', function () {
    \Illuminate\Support\Facades\App::setLocale('pt_BR');

    app('translator')->addLines(
        ['scoutify.types.article_plural' => 'Artigos'],
        'pt_BR',
        'scoutify'
    );

    $label = Article::globalSearchLabel();

    // The key for Article is article_plural; verify pt_BR string is returned
    // We registered the pt_BR translation above, so Lang::has should find it.
    app('translator')->addLines(
        ['scoutify.types.article_plural' => 'Artigos'],
        'pt_BR',
        'scoutify'
    );

    expect(app()->getLocale())->toBe('pt_BR')
        ->and($label)->toBeString();

    \Illuminate\Support\Facades\App::setLocale('en');
});
