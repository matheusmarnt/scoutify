<?php

namespace Matheusmarnt\Scoutify\Tests;

use Matheusmarnt\Scoutify\ScoutifyServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ScoutifyServiceProvider::class,
        ];
    }
}
