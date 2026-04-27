<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;

class InstallCommand extends Command
{
    protected $signature = 'scoutify:install {--driver= : Scout driver to install (algolia|meilisearch|typesense)}';

    protected $description = 'Install and configure Scoutify with a Scout search driver';

    public function handle(): int
    {
        $driver = $this->option('driver') ?? select(
            label: 'Which Scout driver do you want to use?',
            options: ['meilisearch', 'algolia', 'typesense'],
            default: 'meilisearch',
        );

        $packages = [
            'algolia' => 'algolia/algoliasearch-client-php',
            'meilisearch' => 'meilisearch/meilisearch-php http-interop/http-factory-guzzle',
            'typesense' => 'typesense/typesense-php',
        ];

        if (isset($packages[$driver])) {
            spin(
                fn () => $this->runComposerRequire($packages[$driver]),
                "Installing {$driver} driver...",
            );
        }

        $this->callSilent('vendor:publish', ['--tag' => 'scout-config', '--force' => false]);
        $this->callSilent('vendor:publish', ['--tag' => 'scoutify-config', '--force' => false]);

        $this->setEnvValue('SCOUT_DRIVER', $driver);

        info('Scoutify installed. Run php artisan scoutify:searchable to register your models.');

        return self::SUCCESS;
    }

    private function runComposerRequire(string $packages): void
    {
        exec("composer require {$packages} --no-interaction --quiet");
    }

    private function setEnvValue(string $key, string $value): void
    {
        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            return;
        }

        $env = file_get_contents($envPath);

        if (str_contains($env, "{$key}=")) {
            return; // already set — idempotent
        }

        file_put_contents($envPath, $env.PHP_EOL."{$key}={$value}".PHP_EOL);
    }
}
