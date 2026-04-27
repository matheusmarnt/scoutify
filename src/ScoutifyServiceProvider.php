<?php

namespace Matheusmarnt\Scoutify;

use Livewire\Livewire;
use Matheusmarnt\Scoutify\Console\FlushCommand;
use Matheusmarnt\Scoutify\Console\ImportCommand;
use Matheusmarnt\Scoutify\Console\InstallCommand;
use Matheusmarnt\Scoutify\Console\SearchableCommand;
use Matheusmarnt\Scoutify\Console\SyncCommand;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ScoutifyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('scoutify')
            ->hasConfigFile()
            ->hasViews('scoutify')
            ->hasTranslations()
            ->hasCommands([
                InstallCommand::class,
                SearchableCommand::class,
                FlushCommand::class,
                ImportCommand::class,
                SyncCommand::class,
            ]);
    }

    public function packageBooted(): void
    {
        if (class_exists(\Livewire\LivewireManager::class) && $this->app->bound('livewire')) {
            Livewire::component('scoutify::modal', Modal::class);
        }
    }
}
