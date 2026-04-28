<?php

namespace Matheusmarnt\Scoutify;

use Livewire\Livewire;
use Livewire\LivewireManager;
use Matheusmarnt\Scoutify\Console\DoctorCommand;
use Matheusmarnt\Scoutify\Console\FlushCommand;
use Matheusmarnt\Scoutify\Console\ImportCommand;
use Matheusmarnt\Scoutify\Console\InstallCommand;
use Matheusmarnt\Scoutify\Console\RebuildCommand;
use Matheusmarnt\Scoutify\Console\SearchableCommand;
use Matheusmarnt\Scoutify\Console\SyncCommand;
use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;
use Matheusmarnt\Scoutify\Support\LivewireVersion;
use Matheusmarnt\Scoutify\Support\TypeManifest;
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
                DoctorCommand::class,
                InstallCommand::class,
                RebuildCommand::class,
                SearchableCommand::class,
                FlushCommand::class,
                ImportCommand::class,
                SyncCommand::class,
            ]);
    }

    public function packageBooted(): void
    {
        $this->app->singleton(GlobalSearchRegistry::class);

        // Load manifest and populate registry
        $registry = $this->app->make(GlobalSearchRegistry::class);
        foreach (TypeManifest::load() as $class => $meta) {
            $registry->register($class, $meta);
        }

        // If manifest is absent or empty, build it (one-time, non-blocking)
        if (! is_file(TypeManifest::path()) || empty(TypeManifest::load())) {
            try {
                TypeManifest::write(TypeManifest::build());
                // Re-populate now that manifest is fresh
                foreach (TypeManifest::load() as $class => $meta) {
                    $registry->register($class, $meta);
                }
            } catch (\Throwable) {
                // never break boot
            }
        }

        if (LivewireVersion::isV4OrAbove() && class_exists(LivewireManager::class) && ($this->app->bound('livewire') || $this->app->bound(LivewireManager::class))) {
            Livewire::addNamespace('scoutify', classNamespace: 'Matheusmarnt\\Scoutify\\Livewire');
        }
    }
}
