<?php

use Illuminate\Support\Facades\Artisan;
use Matheusmarnt\Scoutify\Services\ModelSourceMutation;
use Matheusmarnt\Scoutify\Services\ModelSourceMutator;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Plain;

it('calls mutator and outputs registration info', function () {
    $mutation = new ModelSourceMutation(
        addedImports: ['Matheusmarnt\\Scoutify\\Concerns\\Searchable', 'Matheusmarnt\\Scoutify\\Contracts\\GloballySearchable'],
        addedInterface: true,
        addedTraitUse: true,
    );

    $mutator = Mockery::mock(ModelSourceMutator::class);
    $mutator->expects('mutate')->with(Plain::class)->andReturn($mutation);
    $this->app->instance(ModelSourceMutator::class, $mutator);

    $exit = Artisan::call('scoutify:searchable', ['model' => Plain::class]);

    expect($exit)->toBe(0);
    $output = Artisan::output();
    expect($output)->toContain('Registered');
    expect($output)->toContain('Imported Matheusmarnt\\Scoutify\\Concerns\\Searchable');
    expect($output)->toContain('Implemented GloballySearchable interface');
    expect($output)->toContain('Added use Searchable; to class body');
});

it('skips and shows already-searchable when mutation is a no-op', function () {
    $mutation = new ModelSourceMutation(addedImports: [], addedInterface: false, addedTraitUse: false);

    $mutator = Mockery::mock(ModelSourceMutator::class);
    $mutator->expects('mutate')->with(Plain::class)->andReturn($mutation);
    $this->app->instance(ModelSourceMutator::class, $mutator);

    $exit = Artisan::call('scoutify:searchable', ['model' => Plain::class]);

    expect($exit)->toBe(0);
    expect(Artisan::output())->toContain('already searchable');
});

it('shows dry-run output with Would add prefix', function () {
    $mutation = new ModelSourceMutation(
        addedImports: ['Matheusmarnt\\Scoutify\\Concerns\\Searchable'],
        addedInterface: true,
        addedTraitUse: false,
    );

    $mutator = Mockery::mock(ModelSourceMutator::class);
    $mutator->expects('mutateFile')->once()->andReturn($mutation);
    $this->app->instance(ModelSourceMutator::class, $mutator);

    $exit = Artisan::call('scoutify:searchable', ['model' => Plain::class, '--dry-run' => true]);

    expect($exit)->toBe(0);
    $output = Artisan::output();
    expect($output)->toContain('Would register');
    expect($output)->toContain('Would add: Imported Matheusmarnt\\Scoutify\\Concerns\\Searchable');
});
