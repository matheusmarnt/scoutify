<?php

use Illuminate\Support\Collection;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\Support\SlotContext;

function makeContext(): SlotContext
{
    return new SlotContext(
        wire: Mockery::mock(Modal::class),
        query: 'foo',
        results: collect([['id' => 1]]),
        hasResults: true,
        isIdle: false,
    );
}

it('exposes constructor properties', function () {
    $ctx = makeContext();

    expect($ctx->query)->toBe('foo')
        ->and($ctx->results)->toBeInstanceOf(Collection::class)
        ->and($ctx->results->all())->toBe([['id' => 1]])
        ->and($ctx->hasResults)->toBeTrue()
        ->and($ctx->isIdle)->toBeFalse();
});

it('results is a Collection instance', function () {
    $ctx = makeContext();

    expect($ctx->results)->toBeInstanceOf(Collection::class);
});

it('results supports Collection methods', function () {
    $ctx = makeContext();

    expect($ctx->results->count())->toBe(1)
        ->and($ctx->results->first())->toBe(['id' => 1])
        ->and($ctx->results->filter()->count())->toBe(1);
});

it('toArray returns all properties keyed correctly', function () {
    $ctx = makeContext();
    $arr = $ctx->toArray();

    expect($arr)->toHaveKeys(['wire', 'query', 'results', 'hasResults', 'isIdle'])
        ->and($arr['query'])->toBe('foo')
        ->and($arr['results'])->toBeInstanceOf(Collection::class)
        ->and($arr['hasResults'])->toBeTrue()
        ->and($arr['isIdle'])->toBeFalse()
        ->and($arr['wire'])->toBe($ctx->wire);
});
