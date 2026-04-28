<?php

namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Laravel\Scout\Builder;

class FilteringArticle extends Article
{
    protected $table = 'articles';

    public static bool $hookCalled = false;

    public static ?string $hookQuery = null;

    public function globalSearchBuilder(Builder $builder, string $query): Builder
    {
        static::$hookCalled = true;
        static::$hookQuery = $query;

        return $builder->whereIn('id', []);
    }
}
