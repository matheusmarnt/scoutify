<?php

namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Authorization\VisibilityRule;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;
use Matheusmarnt\Scoutify\Contracts\HasGlobalSearchVisibility;

class FlagArticle extends Model implements GloballySearchable, HasGlobalSearchVisibility
{
    use Searchable;

    protected $table = 'articles';

    protected $fillable = ['name', 'is_published'];

    public function globalSearchVisibility(): VisibilityRule
    {
        return VisibilityRule::make()
            ->visibleToGuests()
            ->orWhenAuthenticated()
            ->attribute('is_published', true);
    }

    public static function globalSearchIcon(): string
    {
        return 'heroicon-o-flag';
    }

    public function globalSearchUrl(): string
    {
        return '/articles/'.$this->id;
    }
}
