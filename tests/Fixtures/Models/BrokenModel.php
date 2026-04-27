<?php

namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class BrokenModel extends Model
{
    use Searchable;

    protected $table = 'this_table_does_not_exist';
}
