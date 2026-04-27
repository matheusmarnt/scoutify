<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;

use function Laravel\Prompts\info;

class SyncCommand extends Command
{
    protected $signature = 'scoutify:sync {model? : FQCN of a specific model to sync}';

    protected $description = 'Flush and re-import searchable models (flush + import)';

    public function handle(): int
    {
        $modelArg = $this->argument('model') ? ['model' => $this->argument('model')] : [];

        $this->call('scoutify:flush', $modelArg);
        $this->call('scoutify:import', $modelArg);

        info('Sync complete.');

        return self::SUCCESS;
    }
}
