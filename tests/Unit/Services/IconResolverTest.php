<?php

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
