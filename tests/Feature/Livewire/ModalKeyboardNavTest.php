<?php

use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;

// ScoutifyModalAlpineDataIsOpenTest: blade source exposes isOpen getter via $wire
it('scoutifyModal exposes isOpen getter reading from $wire', function () {
    $source = file_get_contents(realpath(__DIR__.'/../../../resources/views/livewire/modal.blade.php'));
    expect($source)
        ->toContain('get isOpen()')
        ->toContain('this.$wire.isOpen');
});

// ModalKeyboardNavArrowDownTest: keydown handlers guard on isOpen + !previewOpen in blade
it('keyboard nav handlers reference isOpen in blade source', function () {
    $source = file_get_contents(realpath(__DIR__.'/../../../resources/views/livewire/modal.blade.php'));
    expect($source)
        ->toContain('isOpen && isFocusInside() && !previewOpen')
        ->toContain('nav(1)')
        ->toContain('nav(-1)')
        ->toContain('navHome()')
        ->toContain('navEnd()')
        ->toContain('navPageDown()')
        ->toContain('navPageUp()')
        ->toContain('navSubAction(1)')
        ->toContain('navSubAction(-1)');
});

// ModalKeyboardNavClosedNoOpTest: modal starts closed, open() sets isOpen = true
it('modal isOpen defaults to false and open() sets it to true', function () {
    Livewire::test(Modal::class)
        ->assertSet('isOpen', false)
        ->call('open')
        ->assertSet('isOpen', true);
});

// ModalKeyboardNavClosedNoOpTest: close() resets isOpen to false
it('close() resets isOpen to false', function () {
    Livewire::test(Modal::class)
        ->call('open')
        ->assertSet('isOpen', true)
        ->call('close')
        ->assertSet('isOpen', false);
});

// ModalKeyboardNavEnterTest: activeIndex resets to 0 on open
it('activeIndex resets to 0 when modal opens', function () {
    Livewire::test(Modal::class)
        ->set('activeIndex', 3)
        ->call('open')
        ->assertSet('activeIndex', 0);
});
