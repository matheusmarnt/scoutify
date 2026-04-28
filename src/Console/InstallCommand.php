<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

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

        $this->injectTailwindImport();

        $this->setEnvValue('SCOUT_DRIVER', $driver);

        match ($this->detectEnvironment()) {
            'sail' => $this->configureForSail($driver),
            'docker' => $this->configureForDocker($driver),
            'host' => $this->configureForHost($driver),
        };

        info('Scoutify installed. Run php artisan scoutify:searchable to register your models.');

        $this->newLine();
        $this->call('scoutify:doctor');

        return self::SUCCESS;
    }

    private function detectEnvironment(): string
    {
        if (is_dir(base_path('vendor/laravel/sail'))) {
            return 'sail';
        }

        if ($this->hasGenericDockerCompose()) {
            return 'docker';
        }

        return 'host';
    }

    private function hasGenericDockerCompose(): bool
    {
        foreach (['docker-compose.yml', 'docker-compose.yaml', 'compose.yml', 'compose.yaml'] as $file) {
            if (file_exists(base_path($file))) {
                return true;
            }
        }

        return false;
    }

    private function configureForSail(string $driver): void
    {
        if ($driver === 'meilisearch') {
            $hasService = $this->composeFilesContain('meilisearch:');

            if (! $hasService && $this->getApplication()?->has('sail:add')) {
                $this->info('Sail detected. Adding meilisearch service to docker-compose.yml...');
                $this->call('sail:add', ['services' => 'meilisearch']);
            }

            // sail:add sets MEILISEARCH_HOST, but update it if it's still the broken localhost default
            $this->updateEnvValue('MEILISEARCH_HOST', 'http://meilisearch:7700', 'http://localhost:7700');
            $this->setEnvValue('SCOUT_QUEUE', 'true');

            $this->newLine();
            $this->line('  <comment>Next:</comment> sail down && sail up -d');

            return;
        }

        if ($driver === 'typesense') {
            $hasService = $this->composeFilesContain('typesense:');

            if (! $hasService && $this->getApplication()?->has('sail:add')) {
                $this->info('Sail detected. Adding typesense service to docker-compose.yml...');
                $this->call('sail:add', ['services' => 'typesense']);
            }

            $this->updateEnvValue('TYPESENSE_HOST', 'typesense', 'localhost');
            $this->setEnvValue('TYPESENSE_PORT', '8108');
            $this->setEnvValue('TYPESENSE_PROTOCOL', 'http');
            $this->setEnvValue('TYPESENSE_API_KEY', 'xyz');

            $this->newLine();
            $this->line('  <comment>Next:</comment> sail down && sail up -d');

            return;
        }

        if ($driver === 'algolia') {
            $this->configureAlgoliaCredentials();
        }
    }

    private function configureForDocker(string $driver): void
    {
        if ($driver === 'meilisearch') {
            $stubDest = base_path('docker-compose.scoutify.yml');

            if (! file_exists($stubDest)) {
                copy(__DIR__.'/../../stubs/docker-compose.scoutify.yml', $stubDest);
                $this->info('Created docker-compose.scoutify.yml at project root.');
            }

            $this->updateEnvValue('MEILISEARCH_HOST', 'http://meilisearch:7700', 'http://localhost:7700');

            $this->newLine();
            $this->line('  <comment>Next:</comment> docker compose -f docker-compose.yml -f docker-compose.scoutify.yml up -d');
            $this->line('  <comment>Note:</comment> Add `meilisearch` to your app service\'s depends_on in docker-compose.yml.');

            return;
        }

        if ($driver === 'typesense') {
            $stubDest = base_path('docker-compose.scoutify.yml');

            if (! file_exists($stubDest)) {
                copy(__DIR__.'/../../stubs/docker-compose.typesense.yml', $stubDest);
                $this->info('Created docker-compose.scoutify.yml at project root.');
            }

            $this->updateEnvValue('TYPESENSE_HOST', 'typesense', 'localhost');
            $this->setEnvValue('TYPESENSE_PORT', '8108');
            $this->setEnvValue('TYPESENSE_PROTOCOL', 'http');
            $this->setEnvValue('TYPESENSE_API_KEY', 'xyz');

            $this->newLine();
            $this->line('  <comment>Next:</comment> docker compose -f docker-compose.yml -f docker-compose.scoutify.yml up -d');
            $this->line('  <comment>Note:</comment> Add `typesense` to your app service\'s depends_on in docker-compose.yml.');

            return;
        }

        if ($driver === 'algolia') {
            $this->configureAlgoliaCredentials();
        }
    }

    private function configureForHost(string $driver): void
    {
        if ($driver === 'meilisearch') {
            $this->setEnvValue('MEILISEARCH_HOST', 'http://localhost:7700');

            $this->newLine();
            $this->line('  <comment>Note:</comment> Meilisearch is not running. Start it with one of:');
            $this->line('    docker run -d --name meilisearch -p 7700:7700 \\');
            $this->line('      -v $(pwd)/meili_data:/meili_data getmeili/meilisearch:latest');
            $this->line('    or: https://www.meilisearch.com/docs/learn/getting_started/installation');

            return;
        }

        if ($driver === 'typesense') {
            $this->setEnvValue('TYPESENSE_HOST', 'localhost');
            $this->setEnvValue('TYPESENSE_PORT', '8108');
            $this->setEnvValue('TYPESENSE_PROTOCOL', 'http');
            $this->setEnvValue('TYPESENSE_API_KEY', 'xyz');

            $this->newLine();
            $this->line('  <comment>Note:</comment> Typesense is not running. Start it with:');
            $this->line('    docker run -d --name typesense -p 8108:8108 \\');
            $this->line('      -v $(pwd)/typesense_data:/data \\');
            $this->line('      typesense/typesense:latest \\');
            $this->line('      --data-dir /data --api-key=xyz --enable-cors');
            $this->line('    or: https://typesense.org/docs/guide/install-typesense.html');

            return;
        }

        if ($driver === 'algolia') {
            $this->configureAlgoliaCredentials();
        }
    }

    private function configureAlgoliaCredentials(): void
    {
        $this->setEnvValue('ALGOLIA_APP_ID', '');
        $this->setEnvValue('ALGOLIA_SECRET', '');

        $this->newLine();
        $this->warn('  Add your Algolia credentials to .env:');
        $this->line('  ALGOLIA_APP_ID=your-app-id');
        $this->line('  ALGOLIA_SECRET=your-admin-api-key');
        $this->line('  Get credentials at: https://www.algolia.com/account/api-keys');
    }

    private function composeFilesContain(string $needle): bool
    {
        foreach (['docker-compose.yml', 'docker-compose.yaml', 'compose.yml', 'compose.yaml'] as $file) {
            $path = base_path($file);
            if (file_exists($path) && str_contains((string) file_get_contents($path), $needle)) {
                return true;
            }
        }

        return false;
    }

    private function injectTailwindImport(): void
    {
        $cssPath = base_path('resources/css/app.css');

        if (! is_file($cssPath)) {
            $this->components->warn('resources/css/app.css not found — add the import manually:');
            $this->line('  @import "../../vendor/matheusmarnt/scoutify/resources/css/scoutify.css";');

            return;
        }

        $contents = (string) file_get_contents($cssPath);
        $importLine = '@import "../../vendor/matheusmarnt/scoutify/resources/css/scoutify.css";';

        if (str_contains($contents, 'matheusmarnt/scoutify/resources/css/scoutify.css')) {
            $this->components->info('Scoutify CSS already imported in resources/css/app.css.');

            return;
        }

        $patched = preg_replace(
            '/(@import\s+["\']tailwindcss["\'];)/',
            "$1\n{$importLine}",
            $contents,
            1,
        );

        if ($patched === null || $patched === $contents) {
            $patched = $importLine."\n".$contents;
        }

        file_put_contents($cssPath, $patched);
        $this->components->info('Added Scoutify @import to resources/css/app.css.');
    }

    private function runComposerRequire(string ...$packages): void
    {
        Process::run(['composer', 'require', ...$packages, '--no-interaction', '--quiet']);
    }

    private function setEnvValue(string $key, string $value): void
    {
        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            return;
        }

        $env = file_get_contents($envPath);

        if (str_contains($env, "{$key}=")) {
            return;
        }

        file_put_contents($envPath, $env.PHP_EOL."{$key}={$value}".PHP_EOL);
    }

    private function updateEnvValue(string $key, string $newValue, string $replaceableDefault = ''): void
    {
        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            return;
        }

        $env = (string) file_get_contents($envPath);
        $pattern = "/^{$key}=(.*)$/m";

        if (preg_match($pattern, $env, $matches)) {
            $current = $matches[1];
            if ($current === '' || ($replaceableDefault !== '' && $current === $replaceableDefault)) {
                file_put_contents($envPath, preg_replace($pattern, "{$key}={$newValue}", $env));
            }

            return;
        }

        file_put_contents($envPath, $env.PHP_EOL."{$key}={$newValue}".PHP_EOL);
    }
}
