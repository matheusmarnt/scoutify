<?php

namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;

class AlreadyRegistered extends Model implements GloballySearchable
{
    use Searchable;

    protected $fillable = ['name'];
}
