<?php

namespace Matheusmarnt\Scoutify;

use Livewire\Livewire;
use Livewire\LivewireManager;
use Matheusmarnt\Scoutify\Authorization\GlobalSearchAuthorizer;
use Matheusmarnt\Scoutify\Console\DoctorCommand;
use Matheusmarnt\Scoutify\Console\FlushCommand;
use Matheusmarnt\Scoutify\Console\ImportCommand;
use Matheusmarnt\Scoutify\Console\InstallCommand;
use Matheusmarnt\Scoutify\Console\RebuildCommand;
use Matheusmarnt\Scoutify\Console\SearchableCommand;
use Matheusmarnt\Scoutify\Console\SyncCommand;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\Services\PreviewResolver;
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
            ->hasRoute('web')
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
        $this->app->singleton(ScoutifyManager::class);

        $this->assertNoLegacyConfigKeys();

        $this->app->singleton(GlobalSearchRegistry::class);
        $this->app->singleton(GlobalSearchAuthorizer::class);
        $this->app->singleton(PreviewResolver::class);

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
        } elseif (class_exists(LivewireManager::class) && ($this->app->bound('livewire') || $this->app->bound(LivewireManager::class))) {
            Livewire::component('scoutify::modal', Modal::class);
        }
    }

    private function assertNoLegacyConfigKeys(): void
    {
        $scoutify = $this->app['config']->get('scoutify', []);

        $found = array_values(array_filter(
            ['icon_prefix', 'types', 'classes', 'colors'],
            fn ($key) => array_key_exists($key, $scoutify),
        ));

        if (isset($scoutify['modal']['ui'])) {
            $found[] = 'modal.ui';
        }

        if (empty($found)) {
            return;
        }

        $keys = implode(', ', $found);

        throw new \RuntimeException(<<<MSG
            Scoutify v2: legacy config keys detected in config/scoutify.php: [{$keys}]

              Upgrade steps:
                1. Delete config/scoutify.php (or remove the listed keys manually)
                2. Re-publish the v2 config:
                     php artisan vendor:publish --tag=scoutify-config --force
                3. Move customizations to AppServiceProvider::boot():
                     Scoutify::types()->register(...)    — replaces 'types'
                     Scoutify::types()->iconPrefix(...)  — replaces 'icon_prefix'
                     Scoutify::theme()->input(...)       — replaces 'classes.input'
                     Scoutify::theme()->color(...)       — replaces 'colors'

              Full upgrade guide: https://matheusmarnt.github.io/scoutify/upgrading/v2
            MSG);
    }
}
