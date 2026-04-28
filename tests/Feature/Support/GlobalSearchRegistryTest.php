<?php

use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;

it('get returns metadata for a registered class', function () {
    $registry = new GlobalSearchRegistry;
    $meta = ['key' => 'users', 'icon' => 'heroicon-o-user', 'color' => 'indigo'];
    $registry->register('App\Models\User', $meta);

    expect($registry->get('App\Models\User'))->toBe($meta);
});

it('get returns null for an unregistered class', function () {
    $registry = new GlobalSearchRegistry;

    expect($registry->get('App\Models\NonExistent'))->toBeNull();
});

it('has returns true for a registered class', function () {
    $registry = new GlobalSearchRegistry;
    $registry->register('App\Models\User', []);

    expect($registry->has('App\Models\User'))->toBeTrue();
});

it('has returns false for an unregistered class', function () {
    $registry = new GlobalSearchRegistry;

    expect($registry->has('App\Models\Unregistered'))->toBeFalse();
});

it('all returns only registered classes', function () {
    $registry = new GlobalSearchRegistry;
    $registry->register('App\Models\User', ['key' => 'users']);
    $registry->register('App\Models\Post', ['key' => 'posts']);

    expect($registry->all())->toHaveCount(2)
        ->toHaveKey('App\Models\User')
        ->toHaveKey('App\Models\Post');
});
