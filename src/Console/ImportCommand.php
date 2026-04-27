<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;

class ImportCommand extends Command
{
    protected $signature = 'scoutify:import';

    protected $description = 'Import all searchable models into the index';

    public function handle(): void {}
}
