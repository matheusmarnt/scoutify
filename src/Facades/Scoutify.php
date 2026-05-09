<?php

namespace Matheusmarnt\Scoutify\Facades;

use Illuminate\Support\Facades\Facade;
use Matheusmarnt\Scoutify\ScoutifyManager;
use Matheusmarnt\Scoutify\Support\ThemeConfig;
use Matheusmarnt\Scoutify\Support\TypesConfig;

/**
 * @method static TypesConfig types()
 * @method static ThemeConfig theme()
 * @method static void configureUi(\Closure $resolver)
 *
 * @see ScoutifyManager
 */
class Scoutify extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ScoutifyManager::class;
    }
}
