<?php

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Matheusmarnt\Scoutify\Support\LivewireVersion;

beforeEach(function () {
    config(['scout.driver' => 'meilisearch', 'scout.meilisearch.host' => 'http://localhost:7700']);
});

afterEach(function () {
    putenv('LARAVEL_SAIL'); // unset
});

it('exits 0 when meilisearch is healthy', function () {
    Http::fake(['http://localhost:7700/health' => Http::response(['status' => 'available'], 200)]);

    $this->artisan('scoutify:doctor')->assertSuccessful();
});

it('exits 1 and prints host remediation when meilisearch unreachable on host', function () {
    Http::fake(['http://localhost:7700/health' => function () {
        throw new ConnectionException('Connection refused');
    }]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('docker run');
});

it('exits 1 and prints sail remediation when meilisearch unreachable in Sail', function () {
    putenv('LARAVEL_SAIL=1');

    Http::fake(['http://localhost:7700/health' => function () {
        throw new ConnectionException('Connection refused');
    }]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('sail down');
});

it('prints sail service missing hint when localhost in Sail', function () {
    putenv('LARAVEL_SAIL=1');

    Http::fake(['http://localhost:7700/health' => function () {
        throw new ConnectionException('Connection refused');
    }]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('MEILISEARCH_HOST=http://meilisearch:7700');
});

it('exits 1 when SCOUT_DRIVER is missing', function () {
    config(['scout.driver' => null]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('SCOUT_DRIVER');
});

it('exits 0 when algolia credentials are present', function () {
    config([
        'scout.driver' => 'algolia',
        'scout.algolia.id' => 'test-app-id',
        'scout.algolia.secret' => 'test-secret',
    ]);

    $this->artisan('scoutify:doctor')->assertSuccessful();
});

it('exits 1 when algolia credentials are missing', function () {
    config([
        'scout.driver' => 'algolia',
        'scout.algolia.id' => '',
        'scout.algolia.secret' => '',
    ]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('ALGOLIA_APP_ID');
});

it('exits 0 for unknown driver without crashing', function () {
    config(['scout.driver' => 'custom-driver']);

    $this->artisan('scoutify:doctor')->assertSuccessful();
});

it('exits 1 when meilisearch returns a non-2xx status', function () {
    Http::fake(['http://localhost:7700/health' => Http::response([], 500)]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('500');
});

it('exits 1 and prints sail-down hint when Sail + non-localhost host unreachable', function () {
    putenv('LARAVEL_SAIL=1');
    config(['scout.meilisearch.host' => 'http://meilisearch:7700']);

    Http::fake(['http://meilisearch:7700/health' => function () {
        throw new ConnectionException('Connection refused');
    }]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('sail down');
});

it('exits 1 with custom-host hint on host with non-localhost meilisearch', function () {
    config(['scout.meilisearch.host' => 'http://search.internal:7700']);

    Http::fake(['http://search.internal:7700/health' => function () {
        throw new ConnectionException('Connection refused');
    }]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('search.internal');
});

it('exits 0 when typesense is healthy', function () {
    config([
        'scout.driver' => 'typesense',
        'scout.typesense.client-settings.nodes' => [
            ['host' => 'localhost', 'port' => '8108', 'protocol' => 'http'],
        ],
    ]);

    Http::fake(['http://localhost:8108/health' => Http::response(['ok' => true], 200)]);

    $this->artisan('scoutify:doctor')->assertSuccessful();
});

it('exits 1 when typesense is unreachable', function () {
    config([
        'scout.driver' => 'typesense',
        'scout.typesense.client-settings.nodes' => [
            ['host' => 'localhost', 'port' => '8108', 'protocol' => 'http'],
        ],
    ]);

    Http::fake(['http://localhost:8108/health' => function () {
        throw new ConnectionException('Connection refused');
    }]);

    $this->artisan('scoutify:doctor')->assertFailed();
});

it('exits 1 when typesense returns a non-2xx status', function () {
    config([
        'scout.driver' => 'typesense',
        'scout.typesense.client-settings.nodes' => [
            ['host' => 'localhost', 'port' => '8108', 'protocol' => 'http'],
        ],
    ]);

    Http::fake(['http://localhost:8108/health' => Http::response([], 503)]);

    $this->artisan('scoutify:doctor')->assertFailed();
});

it('checkLivewireScripts passes when @livewireScripts found in version-appropriate layouts dir', function () {
    Http::fake(['*' => Http::response(['status' => 'available'], 200)]);

    $major = LivewireVersion::major();
    $dir = $major >= 4
        ? resource_path('views/layouts')
        : resource_path('views/components/layouts');

    @mkdir($dir, 0755, true);
    $file = $dir.'/app.blade.php';
    file_put_contents($file, '<html><body>@livewireScripts</body></html>');

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('@livewireScripts found');

    unlink($file);
});

it('checkLivewireScripts warns when no layout files found in version-appropriate dir', function () {
    Http::fake(['*' => Http::response(['status' => 'available'], 200)]);

    $major = LivewireVersion::major();
    $dir = $major >= 4
        ? resource_path('views/layouts')
        : resource_path('views/components/layouts');

    // Remove any blade files so the glob returns empty
    foreach (glob("{$dir}/*.blade.php") ?: [] as $f) {
        unlink($f);
    }

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('No layout files found');
});

it('checkLivewireScripts warning includes detected Livewire major version', function () {
    Http::fake(['*' => Http::response(['status' => 'available'], 200)]);

    $major = LivewireVersion::major();
    $dir = $major >= 4
        ? resource_path('views/layouts')
        : resource_path('views/components/layouts');

    foreach (glob("{$dir}/*.blade.php") ?: [] as $f) {
        unlink($f);
    }

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain("Livewire {$major}");
});

it('checkLivewireScripts warns when layout has no @livewireScripts tag', function () {
    Http::fake(['*' => Http::response(['status' => 'available'], 200)]);

    $major = LivewireVersion::major();
    $dir = $major >= 4
        ? resource_path('views/layouts')
        : resource_path('views/components/layouts');

    @mkdir($dir, 0755, true);
    $file = $dir.'/app.blade.php';
    file_put_contents($file, '<html><body>No scripts here</body></html>');

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('@livewireScripts not found');

    unlink($file);
});

it('checkLivewireScripts finds @livewireScripts in a nested subdirectory', function () {
    Http::fake(['*' => Http::response(['status' => 'available'], 200)]);

    $major = LivewireVersion::major();
    $base = $major >= 4
        ? resource_path('views/layouts')
        : resource_path('views/components/layouts');
    $subdir = $base.'/app';

    @mkdir($subdir, 0755, true);
    $file = $subdir.'/layout.blade.php';
    file_put_contents($file, '<html><body>@livewireScripts</body></html>');

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('@livewireScripts found');

    unlink($file);
    @rmdir($subdir);
});

it('exits 1 when a configured type class does not exist', function () {
    config(['scoutify.types' => ['App\Models\GhostModel' => ['icon' => 'x', 'color' => 'zinc']]]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('not found');
});

it('exits 1 when a configured type does not implement GloballySearchable', function () {
    config([
        'scoutify.types' => [
            \Illuminate\Database\Eloquent\Model::class => ['icon' => 'x', 'color' => 'zinc'],
        ],
    ]);

    Http::fake(['http://localhost:7700/health' => Http::response(['status' => 'available'], 200)]);

    $this->artisan('scoutify:doctor')
        ->assertFailed()
        ->expectsOutputToContain('does not implement GloballySearchable');
});

it('prints success message when all configured types are valid', function () {
    config([
        'scout.driver' => 'algolia',
        'scout.algolia.id' => 'app-id',
        'scout.algolia.secret' => 'secret',
        'scoutify.types' => [
            \Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article::class => ['icon' => 'heroicon-o-document', 'color' => 'blue'],
        ],
    ]);

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('All configured types exist and implement GloballySearchable');
});

it('warns about synchronous indexing when SCOUT_QUEUE is false in production', function () {
    $this->app['env'] = 'production';

    Http::fake(['http://localhost:7700/health' => Http::response(['status' => 'available'], 200)]);

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('SCOUT_QUEUE=false in production');

    $this->app['env'] = 'testing';
});
