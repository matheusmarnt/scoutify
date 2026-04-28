<?php

use Illuminate\Support\Facades\Artisan;
use Matheusmarnt\Scoutify\Services\ModelSourceMutation;
use Matheusmarnt\Scoutify\Services\ModelSourceMutator;
use Matheusmarnt\Scoutify\Support\TypeManifest;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Plain;

beforeEach(function () {
    TypeManifest::forget();
});

afterEach(function () {
    TypeManifest::forget();
});

it('calls scoutify:rebuild after successful mutation', function () {
    $mutation = new ModelSourceMutation(
        addedImports: ['Matheusmarnt\\Scoutify\\Concerns\\Searchable'],
        addedInterface: true,
        addedTraitUse: true,
    );

    $mutator = Mockery::mock(ModelSourceMutator::class);
    $mutator->expects('mutate')->with(Plain::class, Mockery::any())->andReturn($mutation);
    $this->app->instance(ModelSourceMutator::class, $mutator);

    Artisan::call('scoutify:searchable', ['model' => Plain::class]);

    expect(Artisan::output())->toContain('Rebuilding manifest');
});

it('calls scoutify:rebuild even when model is already searchable', function () {
    $mutation = new ModelSourceMutation(addedImports: [], addedInterface: false, addedTraitUse: false);

    $mutator = Mockery::mock(ModelSourceMutator::class);
    $mutator->expects('mutate')->with(Plain::class, Mockery::any())->andReturn($mutation);
    $this->app->instance(ModelSourceMutator::class, $mutator);

    Artisan::call('scoutify:searchable', ['model' => Plain::class]);

    expect(Artisan::output())->toContain('Rebuilding manifest');
});

it('does not call scoutify:rebuild in dry-run mode', function () {
    $mutation = new ModelSourceMutation(
        addedImports: ['Matheusmarnt\\Scoutify\\Concerns\\Searchable'],
        addedInterface: true,
        addedTraitUse: false,
    );

    $mutator = Mockery::mock(ModelSourceMutator::class);
    $mutator->expects('mutateFile')->once()->andReturn($mutation);
    $this->app->instance(ModelSourceMutator::class, $mutator);

    Artisan::call('scoutify:searchable', ['model' => Plain::class, '--dry-run' => true]);

    expect(Artisan::output())->not->toContain('Rebuilding manifest');
});
