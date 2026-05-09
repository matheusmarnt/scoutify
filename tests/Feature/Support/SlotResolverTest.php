<?php

use Illuminate\Contracts\View\View;
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

it('wraps closure returning View as HtmlString', function () {
    $mockView = Mockery::mock(View::class);
    $mockView->shouldReceive('render')->andReturn('<p>view content</p>');

    $slot = fn (SlotContext $ctx) => $mockView;
    $result = SlotResolver::render($slot, makeSlotContext());

    expect($result)->toBeInstanceOf(HtmlString::class)
        ->and((string) $result)->toBe('<p>view content</p>');
});

it('renders string view name via view() helper using ctx data', function () {
    $viewPath = sys_get_temp_dir().'/scoutify_test_slot.blade.php';
    file_put_contents($viewPath, '{{ $query }}');
    app('view')->addLocation(sys_get_temp_dir());

    $result = SlotResolver::render('scoutify_test_slot', makeSlotContext());

    expect((string) $result)->toBe('test');

    @unlink($viewPath);
});
