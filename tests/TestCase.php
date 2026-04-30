<?php

namespace Matheusmarnt\Scoutify\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Laravel\Scout\ScoutServiceProvider;
use Livewire\LivewireServiceProvider;
use Matheusmarnt\Scoutify\ScoutifyServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            ScoutServiceProvider::class,
            LivewireServiceProvider::class,
            ScoutifyServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('scout.driver', 'collection');
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }
}
