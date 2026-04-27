<?php

namespace Matheusmarnt\Scoutify\Tests;

use Laravel\Scout\ScoutServiceProvider;
use Matheusmarnt\Scoutify\ScoutifyServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ScoutServiceProvider::class,
            ScoutifyServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('scout.driver', 'collection');
    }
}
