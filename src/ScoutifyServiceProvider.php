<?php

namespace Matheusmarnt\Scoutify;

use Livewire\Livewire;
use Livewire\LivewireManager;
use Matheusmarnt\Scoutify\Console\DoctorCommand;
use Matheusmarnt\Scoutify\Console\FlushCommand;
use Matheusmarnt\Scoutify\Console\ImportCommand;
use Matheusmarnt\Scoutify\Console\InstallCommand;
use Matheusmarnt\Scoutify\Console\SearchableCommand;
use Matheusmarnt\Scoutify\Console\SyncCommand;
use Matheusmarnt\Scoutify\Support\LivewireVersion;
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
                DoctorCommand::class,
                SearchableCommand::class,
                FlushCommand::class,
                ImportCommand::class,
                SyncCommand::class,
            ]);
    }

    public function packageBooted(): void
    {
        if (LivewireVersion::isV4OrAbove() && class_exists(LivewireManager::class) && ($this->app->bound('livewire') || $this->app->bound(LivewireManager::class))) {
            Livewire::addNamespace('scoutify', classNamespace: 'Matheusmarnt\\Scoutify\\Livewire');
        }
    }
}
