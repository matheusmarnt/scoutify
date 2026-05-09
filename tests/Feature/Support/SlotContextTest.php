<?php

use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\Support\SlotContext;

function makeContext(): SlotContext
{
    return new SlotContext(
        wire: Mockery::mock(Modal::class),
        query: 'foo',
        results: [['id' => 1]],
        hasResults: true,
        isIdle: false,
    );
}

it('exposes constructor properties', function () {
    $ctx = makeContext();

    expect($ctx->query)->toBe('foo')
        ->and($ctx->results)->toBe([['id' => 1]])
        ->and($ctx->hasResults)->toBeTrue()
        ->and($ctx->isIdle)->toBeFalse();
});

it('toArray returns all properties keyed correctly', function () {
    $ctx = makeContext();
    $arr = $ctx->toArray();

    expect($arr)->toHaveKeys(['wire', 'query', 'results', 'hasResults', 'isIdle'])
        ->and($arr['query'])->toBe('foo')
        ->and($arr['results'])->toBe([['id' => 1]])
        ->and($arr['hasResults'])->toBeTrue()
        ->and($arr['isIdle'])->toBeFalse()
        ->and($arr['wire'])->toBe($ctx->wire);
});
