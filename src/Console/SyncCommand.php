<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;

class SyncCommand extends Command
{
    protected $signature = 'scoutify:sync';

    protected $description = 'Sync all searchable models with the index';

    public function handle(): void {}
}
