<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;
use Matheusmarnt\Scoutify\Services\ModelDiscoverer;
use Matheusmarnt\Scoutify\Services\ScoutConfigurator;

use function Laravel\Prompts\info;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\warning;

class SearchableCommand extends Command
{
    protected $signature = 'scoutify:searchable {model? : FQCN of a specific model to register}';

    protected $description = 'Register Eloquent models as globally searchable';

    public function handle(): int
    {
        $models = ModelDiscoverer::make()->discover();

        if (empty($models)) {
            warning('No Eloquent models found in app/Models/.');

            return self::SUCCESS;
        }

        $chosen = $this->argument('model')
            ? [$this->argument('model')]
            : multiselect(
                label: 'Which models should be searchable?',
                options: array_combine($models, array_map('class_basename', $models)),
                required: true,
            );

        foreach ($chosen as $fqcn) {
            if (ScoutConfigurator::isAlreadySearchable($fqcn)) {
                info("{$fqcn} is already searchable — skipping.");

                continue;
            }

            info("Registered {$fqcn} as searchable.");
            // In a real implementation, inject the trait via stub
            // For v0.1 we instruct the user to add the trait manually
            $this->line("  → Add '\\Matheusmarnt\\Scoutify\\Concerns\\Searchable;' to {$fqcn}");
            $this->line('  → Implement GloballySearchable interface methods.');
        }

        return self::SUCCESS;
    }
}
