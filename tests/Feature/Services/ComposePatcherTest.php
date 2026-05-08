<?php

use Matheusmarnt\Scoutify\Services\ComposePatcher;
use Symfony\Component\Yaml\Yaml;

beforeEach(function () {
    $this->tmpDir = sys_get_temp_dir().'/scoutify-compose-patcher-'.uniqid();
    mkdir($this->tmpDir, 0755, true);
});

afterEach(function () {
    if (! is_dir($this->tmpDir)) {
        return;
    }
    foreach (glob($this->tmpDir.'/*') as $f) {
        unlink($f);
    }
    rmdir($this->tmpDir);
});

function fixturePath(string $name): string
{
    return __DIR__.'/../../Fixtures/compose/'.$name;
}

it('converts list-form depends_on entry to map form with condition: service_healthy', function () {
    $target = $this->tmpDir.'/compose.yaml';
    copy(fixturePath('sail-list-form.yaml'), $target);

    $patcher = new ComposePatcher($target);
    $changed = $patcher->patchDependsOn('laravel.test', 'meilisearch');

    expect($changed)->toBeTrue();

    $parsed = Yaml::parseFile($target);
    expect($parsed['services']['laravel.test']['depends_on'])
        ->toBeArray()
        ->toHaveKey('meilisearch')
        ->and($parsed['services']['laravel.test']['depends_on']['meilisearch'])
        ->toBe(['condition' => 'service_healthy']);
});

it('preserves other depends_on entries when converting list to map', function () {
    $target = $this->tmpDir.'/compose.yaml';
    copy(fixturePath('sail-list-form.yaml'), $target);

    (new ComposePatcher($target))->patchDependsOn('laravel.test', 'meilisearch');

    $parsed = Yaml::parseFile($target);
    expect($parsed['services']['laravel.test']['depends_on'])
        ->toHaveKeys(['mysql', 'redis', 'meilisearch']);
});

it('returns false and does not modify file when already patched', function () {
    $target = $this->tmpDir.'/compose.yaml';
    copy(fixturePath('sail-already-patched.yaml'), $target);
    $before = file_get_contents($target);

    $changed = (new ComposePatcher($target))->patchDependsOn('laravel.test', 'meilisearch');

    expect($changed)->toBeFalse();
    expect(file_get_contents($target))->toBe($before);
});

it('creates depends_on map when key is missing', function () {
    $target = $this->tmpDir.'/compose.yaml';
    copy(fixturePath('docker-no-depends.yaml'), $target);

    (new ComposePatcher($target))->patchDependsOn('app', 'meilisearch');

    $parsed = Yaml::parseFile($target);
    expect($parsed['services']['app']['depends_on'])
        ->toBe(['meilisearch' => ['condition' => 'service_healthy']]);
});

it('throws when target service does not exist', function () {
    $target = $this->tmpDir.'/compose.yaml';
    copy(fixturePath('sail-list-form.yaml'), $target);

    expect(fn () => (new ComposePatcher($target))->patchDependsOn('nonexistent', 'meilisearch'))
        ->toThrow(RuntimeException::class, "Service 'nonexistent' not found");
});

it('writes a timestamped backup file on first modification', function () {
    $target = $this->tmpDir.'/compose.yaml';
    copy(fixturePath('sail-list-form.yaml'), $target);
    $original = file_get_contents($target);

    (new ComposePatcher($target))->patchDependsOn('laravel.test', 'meilisearch');

    $backups = glob($this->tmpDir.'/compose.yaml.scoutify-backup-*');
    expect($backups)->toHaveCount(1);
    expect(file_get_contents($backups[0]))->toBe($original);
});
