<?php

namespace Matheusmarnt\Scoutify\Console;

use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;

class DoctorCommand extends Command
{
    protected $signature = 'scoutify:doctor';

    protected $description = 'Verify Scoutify connectivity and configuration';

    public function handle(): int
    {
        $driver = config('scout.driver');

        if (! $driver) {
            $this->error('SCOUT_DRIVER is not set. Run php artisan scoutify:install first.');

            return self::FAILURE;
        }

        $this->line("  Scout driver: <info>{$driver}</info>");

        $passed = $this->checkTypes();
        $this->checkLivewireScripts();
        $this->checkQueueConfig();

        if (! $passed) {
            return self::FAILURE;
        }

        return match ($driver) {
            'meilisearch' => $this->checkMeilisearch(),
            'algolia' => $this->checkAlgolia(),
            'typesense' => $this->checkTypesense(),
            default => $this->checkGeneric($driver),
        };
    }

    private function checkMeilisearch(): int
    {
        $host = config('scout.meilisearch.host', 'http://localhost:7700');

        $this->line("  Meilisearch host: <info>{$host}</info>");

        try {
            $response = Http::timeout(5)->get("{$host}/health");

            if ($response->successful()) {
                $this->line('  <info>✓</info> Meilisearch reachable and healthy.');

                return self::SUCCESS;
            }

            $this->error("✗ Meilisearch responded with status {$response->status()}.");

            return self::FAILURE;
        } catch (ConnectionException $e) {
            $this->error("✗ Cannot reach Meilisearch at {$host}.");
            $this->printMeilisearchRemediation($host);

            return self::FAILURE;
        }
    }

    private function printMeilisearchRemediation(string $host): void
    {
        $inSail = getenv('LARAVEL_SAIL') === '1';
        $inDocker = ! $inSail && file_exists('/.dockerenv');
        $isLocalhost = str_contains($host, 'localhost') || str_contains($host, '127.0.0.1');

        if ($inSail) {
            if ($isLocalhost) {
                $this->warn('  Sail detected but MEILISEARCH_HOST points to localhost (wrong inside container).');
                $this->line('  Fix: set MEILISEARCH_HOST=http://meilisearch:7700 in .env, then:');
                $this->line('       sail down && sail up -d');
                $this->line('  If the meilisearch service is missing from docker-compose.yml:');
                $this->line('       php artisan sail:add meilisearch');
            } else {
                $this->warn('  Meilisearch service unreachable. Container may not be running.');
                $this->line('  Fix: sail down && sail up -d');
            }
        } elseif ($inDocker) {
            if ($isLocalhost) {
                $this->warn('  Inside Docker but MEILISEARCH_HOST points to localhost (wrong inside container).');
                $this->line('  Fix: set MEILISEARCH_HOST=http://meilisearch:7700 in .env');
                $this->line('  Ensure your compose file includes a meilisearch service.');
                $this->line('  scoutify can generate one: php artisan scoutify:install');
            } else {
                $this->warn('  Meilisearch service unreachable. Check that both containers share a network.');
            }
        } else {
            if ($isLocalhost) {
                $this->warn('  No Meilisearch running on localhost:7700. Start one:');
                $this->line('  Option 1 (Docker): docker run -d --name meilisearch -p 7700:7700 \\');
                $this->line('                     -v $(pwd)/meili_data:/meili_data getmeili/meilisearch:latest');
                $this->line('  Option 2 (native): https://www.meilisearch.com/docs/learn/getting_started/installation');
                $this->line('  Option 3 (Sail):   composer require laravel/sail --dev');
                $this->line('                     php artisan sail:install --with=meilisearch');
            } else {
                $this->warn("  Cannot reach {$host}. Verify the service is running and reachable.");
            }
        }
    }

    private function checkAlgolia(): int
    {
        $appId = config('scout.algolia.id', env('ALGOLIA_APP_ID', ''));
        $secret = config('scout.algolia.secret', env('ALGOLIA_SECRET', ''));

        if (! $appId || ! $secret) {
            $this->error('✗ ALGOLIA_APP_ID or ALGOLIA_SECRET not set. Add them to .env.');

            return self::FAILURE;
        }

        $this->line('  <info>✓</info> Algolia credentials present.');

        return self::SUCCESS;
    }

    private function checkTypesense(): int
    {
        $host = config('scout.typesense.client-settings.nodes.0.host', env('TYPESENSE_HOST', 'localhost'));
        $port = config('scout.typesense.client-settings.nodes.0.port', env('TYPESENSE_PORT', '8108'));
        $protocol = config('scout.typesense.client-settings.nodes.0.protocol', env('TYPESENSE_PROTOCOL', 'http'));
        $url = "{$protocol}://{$host}:{$port}/health";

        try {
            $response = Http::timeout(5)->get($url);

            if ($response->successful()) {
                $this->line("  <info>✓</info> Typesense reachable at {$url}.");

                return self::SUCCESS;
            }

            $this->error("✗ Typesense responded with status {$response->status()}.");

            return self::FAILURE;
        } catch (ConnectionException $e) {
            $this->error("✗ Cannot reach Typesense at {$url}.");

            return self::FAILURE;
        }
    }

    private function checkGeneric(string $driver): int
    {
        $this->warn("  Unknown driver '{$driver}'. No connectivity check available.");

        return self::SUCCESS;
    }

    private function checkTypes(): bool
    {
        $types = config('scoutify.types', []);
        $ok = true;

        foreach (array_keys($types) as $class) {
            if (! class_exists($class)) {
                $this->error("✗ Type class [{$class}] does not exist.");
                $ok = false;

                continue;
            }

            if (! is_a($class, GloballySearchable::class, true)) {
                $this->error("✗ [{$class}] does not implement GloballySearchable.");
                $ok = false;
            }
        }

        if ($ok && ! empty($types)) {
            $this->line('  <info>✓</info> All configured types exist and implement GloballySearchable.');
        } elseif (empty($types)) {
            $this->warn('  No types configured in scoutify.types. Add models to enable global search.');
        }

        return $ok;
    }

    private function checkLivewireScripts(): void
    {
        $layouts = glob(resource_path('views/layouts/*.blade.php')) ?: [];

        foreach ($layouts as $layout) {
            $content = file_get_contents($layout);
            if (str_contains($content, '@livewireScripts') || str_contains($content, '@livewire(\'scripts\')')) {
                $this->line('  <info>✓</info> @livewireScripts found in layout.');

                return;
            }
        }

        if (empty($layouts)) {
            $this->warn('  No layout files found in resources/views/layouts. Ensure @livewireScripts is in your app layout.');
        } else {
            $this->warn('  @livewireScripts not found in resources/views/layouts. Add it before </body>.');
        }
    }

    private function checkQueueConfig(): bool
    {
        $queueEnabled = config('scout.queue', false);

        if (app()->environment('production') && ! $queueEnabled) {
            $this->warn('  SCOUT_QUEUE=false in production. Indexing will happen synchronously on requests.');
            $this->line('  Recommendation: set SCOUT_QUEUE=true in .env for production performance.');
        } elseif ($queueEnabled) {
            $this->line('  <info>✓</info> Scout queue enabled.');
        }

        return true;
    }
}
