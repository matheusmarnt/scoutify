<?php

use Illuminate\Support\Facades\Route;
use Matheusmarnt\Scoutify\Services\SearchableStubBuilder;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\AlreadyRegistered;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Plain;

beforeEach(function () {
    if (! class_exists('App\Filament\Resources\PlainResource')) {
        require __DIR__.'/../../Fixtures/Filament/PlainResource.php';
    }
});

it('detects filament v3 resource and returns getUrl stub', function () {
    $plan = (new SearchableStubBuilder)->buildFor(Plain::class);

    expect($plan->urlBody)->toContain("PlainResource::getUrl('view'");
    expect($plan->urlImports)->toContain('App\Filament\Resources\PlainResource');
});

it('returns route stub when named route exists', function () {
    Route::shouldReceive('has')->with(Mockery::any())->andReturn(true);

    // Use a class whose Filament resource does not exist
    $plan = (new SearchableStubBuilder)->buildFor(AlreadyRegistered::class);

    expect($plan->urlBody)->toContain("route('already_registereds.show'");
    expect($plan->urlImports)->toBe([]);
});

it('returns todo stub when no filament resource or route found', function () {
    Route::shouldReceive('has')->with(Mockery::any())->andReturn(false);

    $plan = (new SearchableStubBuilder)->buildFor(AlreadyRegistered::class);

    expect($plan->urlBody)->toContain('TODO');
    expect($plan->urlBody)->toContain("url('/')");
    expect($plan->urlImports)->toBe([]);
});

it('prefers filament resource over route stub', function () {
    Route::shouldReceive('has')->with(Mockery::any())->andReturn(true);

    $plan = (new SearchableStubBuilder)->buildFor(Plain::class);

    expect($plan->urlBody)->toContain('PlainResource::getUrl');
    expect($plan->urlImports)->not->toBe([]);
});
