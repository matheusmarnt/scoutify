<?php

use Matheusmarnt\Scoutify\Services\ModelSourceMutator;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\AlreadyRegistered;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\PartiallyRegistered;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Plain;

beforeEach(function () {
    $this->tmpDir = sys_get_temp_dir().'/scoutify-mutator-'.uniqid();
    mkdir($this->tmpDir, 0777, true);
});

afterEach(function () {
    if (is_dir($this->tmpDir)) {
        array_map('unlink', glob($this->tmpDir.'/*'));
        rmdir($this->tmpDir);
    }
});

function copyFixtureToTemp(string $tmpDir, string $fixtureClass): string
{
    $reflection = new ReflectionClass($fixtureClass);
    $dest = $tmpDir.'/'.basename($reflection->getFileName());
    copy($reflection->getFileName(), $dest);

    return $dest;
}

it('mutates a plain model file', function () {
    $path = copyFixtureToTemp($this->tmpDir, Plain::class);

    $mutator = new ModelSourceMutator;
    $mutation = $mutator->mutateFile($path);

    expect($mutation->alreadyComplete())->toBeFalse();
    expect($mutation->addedImports)->toEqualCanonicalizing([
        'Matheusmarnt\\Scoutify\\Concerns\\Searchable',
        'Matheusmarnt\\Scoutify\\Contracts\\GloballySearchable',
    ]);
    expect($mutation->addedInterface)->toBeTrue();
    expect($mutation->addedTraitUse)->toBeTrue();

    $contents = file_get_contents($path);
    expect($contents)->toContain('use Matheusmarnt\\Scoutify\\Concerns\\Searchable;');
    expect($contents)->toContain('use Matheusmarnt\\Scoutify\\Contracts\\GloballySearchable;');
    expect($contents)->toContain('implements GloballySearchable');
    expect($contents)->toContain('use Searchable;');
});

it('is a no-op on an already-registered model', function () {
    $path = copyFixtureToTemp($this->tmpDir, AlreadyRegistered::class);
    $before = file_get_contents($path);

    $mutator = new ModelSourceMutator;
    $mutation = $mutator->mutateFile($path);

    expect($mutation->alreadyComplete())->toBeTrue();
    expect(file_get_contents($path))->toBe($before);
});

it('tops up missing pieces on a partial model', function () {
    $path = copyFixtureToTemp($this->tmpDir, PartiallyRegistered::class);

    $mutator = new ModelSourceMutator;
    $mutation = $mutator->mutateFile($path);

    expect($mutation->addedImports)->toBe(['Matheusmarnt\\Scoutify\\Contracts\\GloballySearchable']);
    expect($mutation->addedInterface)->toBeTrue();
    expect($mutation->addedTraitUse)->toBeFalse();

    $contents = file_get_contents($path);
    expect($contents)->toContain('implements GloballySearchable');
    expect(substr_count($contents, 'use Searchable;'))->toBe(1);
});

it('is idempotent on re-run', function () {
    $path = copyFixtureToTemp($this->tmpDir, Plain::class);

    $mutator = new ModelSourceMutator;
    $first = $mutator->mutateFile($path);
    $afterFirst = file_get_contents($path);

    $second = $mutator->mutateFile($path);

    expect($first->alreadyComplete())->toBeFalse();
    expect($second->alreadyComplete())->toBeTrue();
    expect(file_get_contents($path))->toBe($afterFirst);
});
