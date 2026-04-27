<?php

namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ActivePost extends Model
{
    use Searchable;

    protected $table = 'active_posts';

    protected $fillable = ['name', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}
