<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;

class SearchableCommand extends Command
{
    protected $signature = 'scoutify:searchable';

    protected $description = 'Make a model searchable with Scoutify';

    public function handle(): void {}
}
