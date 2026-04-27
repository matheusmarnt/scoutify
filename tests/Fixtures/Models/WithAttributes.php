<?php

namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;

#[\AllowDynamicProperties]
class WithAttributes extends Model
{
    protected $fillable = ['name'];
}
