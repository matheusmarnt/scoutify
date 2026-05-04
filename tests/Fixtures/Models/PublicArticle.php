<?php

namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Authorization\VisibilityRule;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;
use Matheusmarnt\Scoutify\Contracts\HasGlobalSearchVisibility;

class PublicArticle extends Model implements GloballySearchable, HasGlobalSearchVisibility
{
    use Searchable;

    protected $table = 'articles';

    protected $fillable = ['name', 'body'];

    public function globalSearchVisibility(): VisibilityRule
    {
        return VisibilityRule::make()->visibleToGuests();
    }

    public static function globalSearchIcon(): string
    {
        return 'heroicon-o-document';
    }

    public function globalSearchUrl(): string
    {
        return '/articles/'.$this->id;
    }
}
