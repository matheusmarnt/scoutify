<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'scoutify:install';

    protected $description = 'Install the Scoutify package';

    public function handle(): void {}
}
