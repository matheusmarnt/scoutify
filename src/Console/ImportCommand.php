<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;

use function Laravel\Prompts\info;

class ImportCommand extends Command
{
    protected $signature = 'scoutify:import {model? : FQCN of a specific model to import}';

    protected $description = 'Import all searchable models into the Scout index';

    public function handle(): int
    {
        $types = config('scoutify.types', []);

        if (empty($types) && ! $this->argument('model')) {
            $this->warn('No types configured in config/scoutify.php.');

            return self::SUCCESS;
        }

        $targets = $this->argument('model')
            ? [$this->argument('model') => []]
            : $types;

        foreach (array_keys($targets) as $model) {
            $this->callSilent('scout:import', ['model' => $model]);
            info("Imported {$model}.");
        }

        return self::SUCCESS;
    }
}
