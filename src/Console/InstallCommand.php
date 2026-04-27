<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;

class InstallCommand extends Command
{
    protected $signature = 'scoutify:install {--driver= : Scout driver to install (algolia|meilisearch|typesense)}';

    protected $description = 'Install and configure Scoutify with a Scout search driver';

    /** @var array<string, list<string>> */
    private const DRIVER_PACKAGES = [
        'algolia' => ['algolia/algoliasearch-client-php'],
        'meilisearch' => ['meilisearch/meilisearch-php', 'http-interop/http-factory-guzzle'],
        'typesense' => ['typesense/typesense-php'],
    ];

    public function handle(): int
    {
        $allowedDrivers = array_keys(self::DRIVER_PACKAGES);

        $driver = $this->option('driver') ?? select(
            label: 'Which Scout driver do you want to use?',
            options: $allowedDrivers,
            default: 'meilisearch',
        );

        if (! in_array($driver, $allowedDrivers, strict: true)) {
            $this->error("Unknown driver '{$driver}'. Allowed: ".implode(', ', $allowedDrivers));

            return self::FAILURE;
        }

        spin(
            fn () => $this->runComposerRequire(...self::DRIVER_PACKAGES[$driver]),
            "Installing {$driver} driver...",
        );

        $this->call('vendor:publish', ['--tag' => 'scoutify-config']);

        $this->setEnvValue('SCOUT_DRIVER', $driver);

        info('Scoutify installed. Run php artisan scoutify:searchable to register your models.');

        return self::SUCCESS;
    }

    private function runComposerRequire(string ...$packages): void
    {
        $process = new Process(['composer', 'require', ...$packages, '--no-interaction', '--quiet']);
        $process->run();
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
