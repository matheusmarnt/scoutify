<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;
use Matheusmarnt\Scoutify\ScoutifyManager;
use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;

use function Laravel\Prompts\info;

class ImportCommand extends Command
{
    protected $signature = 'scoutify:import {model? : FQCN of a specific model to import}';

    protected $description = 'Import all searchable models into the Scout index';

    public function handle(): int
    {
        $registryTypes = app()->bound(GlobalSearchRegistry::class)
            ? app(GlobalSearchRegistry::class)->all()
            : [];
        $fluentTypes = app()->bound(ScoutifyManager::class)
            ? app(ScoutifyManager::class)->types()->all()
            : [];
        $types = array_merge($registryTypes, $fluentTypes);

        if (empty($types) && ! $this->argument('model')) {
            $this->warn('No types discovered. Register models with Searchable trait, then run scoutify:rebuild.');

            return self::SUCCESS;
        }

        $targets = $this->argument('model')
            ? [$this->argument('model') => []]
            : $types;

        foreach (array_keys($targets) as $model) {
            $exitCode = $this->call('scout:import', ['model' => $model]);

            if ($exitCode !== self::SUCCESS) {
                return self::FAILURE;
            }

            info("Imported {$model}.");
        }

        return self::SUCCESS;
    }
}
