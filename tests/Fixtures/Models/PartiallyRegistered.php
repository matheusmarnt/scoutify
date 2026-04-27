<?php

namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Concerns\Searchable;

class PartiallyRegistered extends Model
{
    use Searchable;

    protected $fillable = ['name'];
}
