<?php

namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Attribute;
use Illuminate\Database\Eloquent\Model;

#[Attribute]
class WithAttributes extends Model
{
    protected $fillable = ['name'];
}
