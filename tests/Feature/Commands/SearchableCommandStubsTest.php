<?php

use Illuminate\Support\Facades\Artisan;
use Matheusmarnt\Scoutify\Services\ModelSourceMutation;
use Matheusmarnt\Scoutify\Services\ModelSourceMutator;
use Matheusmarnt\Scoutify\Services\SearchableStubBuilder;
use Matheusmarnt\Scoutify\Services\StubPlan;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Plain;

it('passes stub plan to mutator by default', function () {
    $stubPlan = new StubPlan(urlBody: "return route('plains.show', \$this);");
    $mutation = new ModelSourceMutation(
        addedImports: ['Matheusmarnt\\Scoutify\\Concerns\\Searchable'],
        addedInterface: true,
        addedTraitUse: true,
        addedUrlStub: true,
    );

    $stubBuilder = Mockery::mock(SearchableStubBuilder::class);
    $stubBuilder->expects('buildFor')->with(Plain::class)->andReturn($stubPlan);
    $this->app->instance(SearchableStubBuilder::class, $stubBuilder);

    $mutator = Mockery::mock(ModelSourceMutator::class);
    $mutator->expects('mutate')->with(Plain::class, $stubPlan)->andReturn($mutation);
    $this->app->instance(ModelSourceMutator::class, $mutator);

    $exit = Artisan::call('scoutify:searchable', ['model' => Plain::class]);

    expect($exit)->toBe(0);
    expect(Artisan::output())->toContain('Injected globalSearchUrl() stub');
});

it('passes null stub plan with --no-stubs', function () {
    $mutation = new ModelSourceMutation(
        addedImports: ['Matheusmarnt\\Scoutify\\Concerns\\Searchable'],
        addedInterface: true,
        addedTraitUse: true,
        addedUrlStub: false,
    );

    $stubBuilder = Mockery::mock(SearchableStubBuilder::class);
    $stubBuilder->shouldNotReceive('buildFor');
    $this->app->instance(SearchableStubBuilder::class, $stubBuilder);

    $mutator = Mockery::mock(ModelSourceMutator::class);
    $mutator->expects('mutate')->with(Plain::class, null)->andReturn($mutation);
    $this->app->instance(ModelSourceMutator::class, $mutator);

    $exit = Artisan::call('scoutify:searchable', ['model' => Plain::class, '--no-stubs' => true]);

    expect($exit)->toBe(0);
    expect(Artisan::output())->not->toContain('globalSearchUrl');
});

it('reports stub injection in dry-run output', function () {
    $stubPlan = new StubPlan(urlBody: "return route('plains.show', \$this);");
    $mutation = new ModelSourceMutation(
        addedImports: ['Matheusmarnt\\Scoutify\\Concerns\\Searchable'],
        addedInterface: true,
        addedTraitUse: true,
        addedUrlStub: true,
    );

    $stubBuilder = Mockery::mock(SearchableStubBuilder::class);
    $stubBuilder->expects('buildFor')->with(Plain::class)->andReturn($stubPlan);
    $this->app->instance(SearchableStubBuilder::class, $stubBuilder);

    $mutator = Mockery::mock(ModelSourceMutator::class);
    $mutator->expects('mutateFile')->withAnyArgs()->andReturn($mutation);
    $this->app->instance(ModelSourceMutator::class, $mutator);

    $exit = Artisan::call('scoutify:searchable', ['model' => Plain::class, '--dry-run' => true]);

    expect($exit)->toBe(0);
    expect(Artisan::output())->toContain('Would add: Injected globalSearchUrl() stub');
});
