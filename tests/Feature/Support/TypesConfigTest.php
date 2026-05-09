<?php

use Matheusmarnt\Scoutify\Support\TypesConfig;

it('starts empty', function () {
    $types = new TypesConfig;

    expect($types->all())->toBeEmpty()
        ->and($types->getIconPrefix())->toBe('');
});

it('fluent methods return $this', function () {
    $types = new TypesConfig;

    expect($types->iconPrefix('heroicon-o-'))->toBe($types)
        ->and($types->register('App\Models\User', label: 'User'))->toBe($types);
});

it('register indexes by FQCN', function () {
    $types = new TypesConfig;
    $types->register('App\Models\User', label: 'Usuário', icon: 'user', color: 'indigo');

    $all = $types->all();
    expect($all)->toHaveKey('App\Models\User')
        ->and($all['App\Models\User']['label'])->toBe('Usuário')
        ->and($all['App\Models\User']['color'])->toBe('indigo');
});

it('get returns null for unregistered class', function () {
    $types = new TypesConfig;

    expect($types->get('App\Models\Nonexistent'))->toBeNull();
});

it('get returns meta for registered class', function () {
    $types = new TypesConfig;
    $types->register('App\Models\Role', label: 'Role', color: 'amber');

    expect($types->get('App\Models\Role'))->toMatchArray(['label' => 'Role', 'color' => 'amber']);
});

it('iconPrefix stored and returned', function () {
    $types = new TypesConfig;
    $types->iconPrefix('heroicon-o-');

    expect($types->getIconPrefix())->toBe('heroicon-o-');
});

it('multiple registrations coexist', function () {
    $types = new TypesConfig;
    $types->register('App\Models\User', label: 'User')
        ->register('App\Models\Role', label: 'Role');

    expect($types->all())->toHaveCount(2);
});
