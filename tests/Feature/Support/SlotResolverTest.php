<?php

use Illuminate\Support\HtmlString;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\Support\SlotContext;
use Matheusmarnt\Scoutify\Support\SlotResolver;

function makeSlotContext(): SlotContext
{
    return new SlotContext(
        wire: Mockery::mock(Modal::class),
        query: 'test',
        results: [],
        hasResults: false,
        isIdle: false,
    );
}

it('returns empty HtmlString for null slot', function () {
    $result = SlotResolver::render(null, makeSlotContext());

    expect($result)->toBeInstanceOf(HtmlString::class)
        ->and((string) $result)->toBe('');
});

it('renders closure result as HtmlString', function () {
    $slot = fn (SlotContext $ctx) => '<span>'.$ctx->query.'</span>';
    $result = SlotResolver::render($slot, makeSlotContext());

    expect($result)->toBeInstanceOf(HtmlString::class)
        ->and((string) $result)->toBe('<span>test</span>');
});

it('passes SlotContext to closure', function () {
    $received = null;
    $slot = function (SlotContext $ctx) use (&$received) {
        $received = $ctx;

        return '';
    };

    SlotResolver::render($slot, makeSlotContext());

    expect($received)->toBeInstanceOf(SlotContext::class)
        ->and($received->query)->toBe('test');
});

it('throws InvalidArgumentException for invalid slot type', function () {
    SlotResolver::render(42, makeSlotContext());
})->throws(InvalidArgumentException::class);

it('wraps HtmlString closure result without double-escaping', function () {
    $slot = fn () => new HtmlString('<b>bold</b>');
    $result = SlotResolver::render($slot, makeSlotContext());

    expect((string) $result)->toBe('<b>bold</b>');
});
