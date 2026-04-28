<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;
use Matheusmarnt\Scoutify\Support\TypeManifest;

class RebuildCommand extends Command
{
    protected $signature = 'scoutify:rebuild';

    protected $description = 'Rebuild the Scoutify type discovery manifest';

    public function handle(): int
    {
        $this->line('  Scanning for Searchable models...');
        TypeManifest::forget();
        $manifest = TypeManifest::build();
        TypeManifest::write($manifest);
        $count = count($manifest);
        $this->line("  <info>✓</info> Manifest rebuilt — {$count} ".($count === 1 ? 'type' : 'types').' registered.');

        return self::SUCCESS;
    }
}
