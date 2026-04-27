<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;

use function Laravel\Prompts\info;

class FlushCommand extends Command
{
    protected $signature = 'scoutify:flush {model? : FQCN of a specific model to flush}';

    protected $description = 'Flush all searchable models from the Scout index';

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
            $this->callSilent('scout:flush', ['model' => $model]);
            info("Flushed {$model}.");
        }

        return self::SUCCESS;
    }
}
