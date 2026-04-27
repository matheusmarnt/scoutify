<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;
use Matheusmarnt\Scoutify\Services\ModelDiscoverer;
use Matheusmarnt\Scoutify\Services\ModelSourceMutation;
use Matheusmarnt\Scoutify\Services\ModelSourceMutator;
use Matheusmarnt\Scoutify\Services\ScoutConfigurator;
use ReflectionClass;
use Throwable;

use function Laravel\Prompts\info;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\warning;

class SearchableCommand extends Command
{
    protected $signature = 'scoutify:searchable
        {model? : FQCN of a specific model to register}
        {--all : Register all discovered models without prompting}
        {--dry-run : Show planned mutations without writing files}';

    protected $description = 'Register Eloquent models as globally searchable';

    public function handle(ModelSourceMutator $mutator): int
    {
        if ($this->argument('model')) {
            $chosen = [$this->argument('model')];
        } else {
            $models = ModelDiscoverer::make()->discover();

            if (empty($models)) {
                warning('No Eloquent models found in app/Models/.');

                return self::SUCCESS;
            }

            $chosen = $this->option('all') || ! $this->input->isInteractive()
                ? $models
                : multiselect(
                    label: 'Which models should be searchable?',
                    options: array_combine($models, array_map('class_basename', $models)),
                    required: true,
                );
        }

        $dryRun = (bool) $this->option('dry-run');

        foreach ($chosen as $fqcn) {
            if (ScoutConfigurator::isAlreadySearchable($fqcn)) {
                info("{$fqcn} is already searchable — skipping.");

                continue;
            }

            try {
                $mutation = $dryRun
                    ? $this->planMutation($mutator, $fqcn)
                    : $mutator->mutate($fqcn);
            } catch (Throwable $e) {
                warning("Failed to mutate {$fqcn}: {$e->getMessage()}");

                continue;
            }

            if ($mutation->alreadyComplete()) {
                info("{$fqcn} is already searchable — skipping.");

                continue;
            }

            $verb = $dryRun ? 'Would register' : 'Registered';
            info("{$verb} {$fqcn} as searchable.");

            $prefix = $dryRun ? '  → Would add: ' : '  → ';
            foreach ($mutation->summary() as $line) {
                $this->line($prefix.$line);
            }
        }

        return self::SUCCESS;
    }

    private function planMutation(ModelSourceMutator $mutator, string $fqcn): ModelSourceMutation
    {
        $reflection = new ReflectionClass($fqcn);
        $original = $reflection->getFileName();

        $tmp = tempnam(sys_get_temp_dir(), 'scoutify-dry-');
        copy($original, $tmp);

        try {
            return $mutator->mutateFile($tmp);
        } finally {
            @unlink($tmp);
        }
    }
}
