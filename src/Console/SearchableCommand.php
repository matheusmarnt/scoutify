<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;
use Matheusmarnt\Scoutify\Services\ModelDiscoverer;
use Matheusmarnt\Scoutify\Services\ModelSourceMutation;
use Matheusmarnt\Scoutify\Services\ModelSourceMutator;
use Matheusmarnt\Scoutify\Services\ScoutConfigurator;
use Matheusmarnt\Scoutify\Services\SearchableStubBuilder;
use Matheusmarnt\Scoutify\Services\StubPlan;
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
        {--dry-run : Show planned mutations without writing files}
        {--no-stubs : Skip injecting the globalSearchUrl() stub into the model}';

    protected $description = 'Register Eloquent models as globally searchable';

    public function handle(ModelSourceMutator $mutator, SearchableStubBuilder $stubBuilder): int
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
        $withStubs = ! $this->option('no-stubs');

        foreach ($chosen as $fqcn) {
            if (ScoutConfigurator::isAlreadySearchable($fqcn)) {
                info("{$fqcn} is already searchable — skipping.");

                continue;
            }

            $stubPlan = $withStubs ? $stubBuilder->buildFor($fqcn) : null;

            try {
                $mutation = $dryRun
                    ? $this->planMutation($mutator, $fqcn, $stubPlan)
                    : $mutator->mutate($fqcn, $stubPlan);
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

        if (! $dryRun) {
            $this->newLine();
            $this->line('  Rebuilding manifest...');
            $this->call('scoutify:rebuild');
        }

        return self::SUCCESS;
    }

    private function planMutation(ModelSourceMutator $mutator, string $fqcn, ?StubPlan $stubPlan = null): ModelSourceMutation
    {
        $reflection = new ReflectionClass($fqcn);
        $original = $reflection->getFileName();

        if (! $original) {
            throw new \RuntimeException("Cannot resolve source file for: {$fqcn}");
        }

        $tmp = tempnam(sys_get_temp_dir(), 'scoutify-dry-');
        copy($original, $tmp);

        try {
            return $mutator->mutateFile($tmp, $stubPlan);
        } finally {
            @unlink($tmp);
        }
    }
}
