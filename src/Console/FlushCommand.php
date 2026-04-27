<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;

class FlushCommand extends Command
{
    protected $signature = 'scoutify:flush';

    protected $description = 'Flush all searchable models from the index';

    public function handle(): void {}
}
