<?php

namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class SoftPost extends Model
{
    use Searchable, SoftDeletes;

    protected $table = 'soft_posts';

    protected $fillable = ['name'];
}
