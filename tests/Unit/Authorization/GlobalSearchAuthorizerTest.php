<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Matheusmarnt\Scoutify\Authorization\GlobalSearchAuthorizer;
use Matheusmarnt\Scoutify\Authorization\VisibilityRule;
use Matheusmarnt\Scoutify\Contracts\HasGlobalSearchVisibility;

it('delegates to model if it implements HasGlobalSearchVisibility', function () {
    $authorizer = new GlobalSearchAuthorizer;
    $user = Mockery::mock(Authenticatable::class);
    $model = Mockery::mock(Model::class, HasGlobalSearchVisibility::class);
    $rule = Mockery::mock(VisibilityRule::class);

    $model->shouldReceive('globalSearchVisibility')->andReturn($rule);
    $rule->shouldReceive('evaluate')->with($model, $user)->andReturn(true);

    expect($authorizer->authorize($model, $user))->toBeTrue();
});

it('uses secure default for non-contract models (guest denied)', function () {
    $authorizer = new GlobalSearchAuthorizer;
    $model = Mockery::mock(Model::class);

    Config::set('scoutify.authorization.default', 'secure');
    Config::set('scoutify.authorization.gate_ability', 'view');

    expect($authorizer->authorize($model, null))->toBeFalse();
});

it('uses secure default for non-contract models (auth checks gate if policy exists)', function () {
    $authorizer = new GlobalSearchAuthorizer;
    $user = Mockery::mock(Authenticatable::class);
    $model = Mockery::mock(Model::class);

    Config::set('scoutify.authorization.default', 'secure');
    Config::set('scoutify.authorization.gate_ability', 'view');

    Gate::shouldReceive('getPolicyFor')->with($model)->andReturn(new stdClass);
    Gate::shouldReceive('forUser')->with($user)->andReturn($gate = Mockery::mock());
    $gate->shouldReceive('check')->with('view', $model)->andReturn(true);

    expect($authorizer->authorize($model, $user))->toBeTrue();
});

it('uses secure default for non-contract models (auth allowed if no policy or gate exists)', function () {
    $authorizer = new GlobalSearchAuthorizer;
    $user = Mockery::mock(Authenticatable::class);
    $model = Mockery::mock(Model::class);

    Config::set('scoutify.authorization.default', 'secure');

    Gate::shouldReceive('getPolicyFor')->with($model)->andReturn(null);
    Gate::shouldReceive('has')->with('view')->andReturn(false);

    expect($authorizer->authorize($model, $user))->toBeTrue();
});

it('uses secure default for non-contract models (auth checks gate if it exists)', function () {
    $authorizer = new GlobalSearchAuthorizer;
    $user = Mockery::mock(Authenticatable::class);
    $model = Mockery::mock(Model::class);

    Config::set('scoutify.authorization.default', 'secure');
    Config::set('scoutify.authorization.gate_ability', 'view');

    Gate::shouldReceive('getPolicyFor')->with($model)->andReturn(null);
    Gate::shouldReceive('has')->with('view')->andReturn(true);
    Gate::shouldReceive('forUser')->with($user)->andReturn($gate = Mockery::mock());
    $gate->shouldReceive('check')->with('view', $model)->andReturn(true);

    expect($authorizer->authorize($model, $user))->toBeTrue();
});

it('supports permissive default', function () {
    $authorizer = new GlobalSearchAuthorizer;
    $model = Mockery::mock(Model::class);

    Config::set('scoutify.authorization.default', 'permissive');

    expect($authorizer->authorize($model, null))->toBeTrue();
    expect($authorizer->authorize($model, Mockery::mock(Authenticatable::class)))->toBeTrue();
});

it('supports gate-only default', function () {
    $authorizer = new GlobalSearchAuthorizer;
    $user = Mockery::mock(Authenticatable::class);
    $model = Mockery::mock(Model::class);

    Config::set('scoutify.authorization.default', 'gate-only');
    Config::set('scoutify.authorization.gate_ability', 'view');

    Gate::shouldReceive('forUser')->with($user)->andReturn($gate = Mockery::mock());
    $gate->shouldReceive('check')->with('view', $model)->andReturn(false);

    expect($authorizer->authorize($model, $user))->toBeFalse();
});
