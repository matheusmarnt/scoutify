<?php

use BladeUI\Icons\Factory;
use Matheusmarnt\Scoutify\Services\IconResolver;

it('adds prefix to short icon name', function () {
    $resolver = new IconResolver('heroicon-o-');
    expect($resolver->resolve('star'))->toBe('heroicon-o-star');
});

it('does not double-prefix already-prefixed icon', function () {
    $resolver = new IconResolver('heroicon-o-');
    expect($resolver->resolve('heroicon-o-star'))->toBe('heroicon-o-star');
});

it('returns icon with slash as-is', function () {
    $resolver = new IconResolver('heroicon-o-');
    expect($resolver->resolve('fas/star'))->toBe('fas/star');
});

it('exposes prefix()', function () {
    $resolver = new IconResolver('heroicon-s-');
    expect($resolver->prefix())->toBe('heroicon-s-');
});

it('passes through remix icon when ri prefix is registered', function () {
    $dir = sys_get_temp_dir().'/scoutify-ri-'.uniqid();
    mkdir($dir, 0755, true);
    app(Factory::class)->add('remix', ['prefix' => 'ri', 'paths' => [$dir]]);

    $resolver = new IconResolver('heroicon-o-');
    expect($resolver->resolve('ri-customer-service-2-fill'))->toBe('ri-customer-service-2-fill');

    rmdir($dir);
});

it('passes through tabler icon when tabler prefix is registered', function () {
    $dir = sys_get_temp_dir().'/scoutify-tabler-'.uniqid();
    mkdir($dir, 0755, true);
    app(Factory::class)->add('tabler', ['prefix' => 'tabler', 'paths' => [$dir]]);

    $resolver = new IconResolver('heroicon-o-');
    expect($resolver->resolve('tabler-home'))->toBe('tabler-home');

    rmdir($dir);
});

it('prepends default prefix when icon prefix is not registered', function () {
    $resolver = new IconResolver('heroicon-o-');
    expect($resolver->resolve('ri-home'))->toBe('heroicon-o-ri-home');
});
