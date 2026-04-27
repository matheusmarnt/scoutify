<?php

use Matheusmarnt\Scoutify\Services\ModelDiscoverer;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('returns empty array when path does not exist', function () {
    $discoverer = new ModelDiscoverer('/non/existent/path');
    expect($discoverer->discover())->toBe([]);
});

it('can be constructed with custom path', function () {
    $discoverer = new ModelDiscoverer('/tmp');
    expect($discoverer)->toBeInstanceOf(ModelDiscoverer::class);
});

it('uses custom namespace when provided', function () {
    $discoverer = new ModelDiscoverer('/tmp', 'Custom\\Models\\');
    expect($discoverer)->toBeInstanceOf(ModelDiscoverer::class);
});

it('discovers eloquent models in a directory', function () {
    $path = realpath(__DIR__.'/../../Fixtures/Models');
    $discoverer = new ModelDiscoverer($path, 'Matheusmarnt\\Scoutify\\Tests\\Fixtures\\Models\\');

    expect($discoverer->discover())->toContain(Article::class);
});

it('skips non-model php classes in a directory', function () {
    $path = realpath(__DIR__.'/../../../src/Services');
    $discoverer = new ModelDiscoverer($path, 'Matheusmarnt\\Scoutify\\Services\\');

    expect($discoverer->discover())->toBe([]);
});

it('make() creates instance with defaults', function () {
    expect(ModelDiscoverer::make())->toBeInstanceOf(ModelDiscoverer::class);
});

it('make() accepts custom basePath and namespace', function () {
    $discoverer = ModelDiscoverer::make('/non/existent', 'Custom\\');

    expect($discoverer->discover())->toBe([]);
});

it('skips php files whose FQCN does not exist as a class', function () {
    $dir = sys_get_temp_dir().'/scoutify-test-'.uniqid();
    mkdir($dir, 0755, true);
    file_put_contents($dir.'/Phantom.php', '<?php'.PHP_EOL.'// no class');

    $discoverer = new ModelDiscoverer($dir, 'NonExistent\\Namespace\\');
    $result = $discoverer->discover();

    expect($result)->toBe([]);

    unlink($dir.'/Phantom.php');
    rmdir($dir);
});
