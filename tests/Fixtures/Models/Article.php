<?php

namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;

class Article extends Model implements GloballySearchable
{
    use Searchable;

    protected $fillable = ['name', 'body'];

    public static function globalSearchGroup(): string
    {
        return 'articles';
    }

    public static function globalSearchIcon(): string
    {
        return 'heroicon-o-document';
    }

    public static function globalSearchColor(): string
    {
        return 'blue';
    }
}
