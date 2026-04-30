<?php

namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;

class RiService extends Model implements GloballySearchable
{
    use Searchable;

    protected $fillable = ['name'];

    public static function globalSearchGroup(): string
    {
        return 'services';
    }

    public static function globalSearchIcon(): string
    {
        return 'ri-customer-service-2-fill';
    }

    public static function globalSearchColor(): string
    {
        return 'blue';
    }
}
