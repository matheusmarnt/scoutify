@props([
    'wire' => 'isOpen',
    'id' => 'scoutify-search',
    'ariaLabel' => null,
])

@php
    $scrimClass = config('scoutify.classes.dialog_scrim', '');
    $panelClass = config('scoutify.classes.dialog_panel', '');
@endphp

<div
    x-data="{
        open: @entangle($wire),
        init() {
            this.$watch('open', value => {
                if (value) {
                    document.body.classList.add('overflow-hidden');
                    this.$nextTick(() => window.dispatchEvent(new CustomEvent('scoutify:opened')));
                } else {
                    document.body.classList.remove('overflow-hidden');
                    window.dispatchEvent(new CustomEvent('scoutify:closed'));
                }
            });
        },
    }"
    x-cloak
    x-show="open"
    @keydown.escape.window="open = false"
    @keydown.window="
        if (open && event.altKey && /^[1-9]$/.test(event.key)) {
            event.preventDefault();
            const idx = parseInt(event.key) - 1;
            document.querySelectorAll('[data-search-result]')[idx]?.click();
        }
    "
    id="{{ $id }}"
    role="dialog"
    aria-modal="true"
    aria-label="{{ $ariaLabel ?? __('scoutify::scoutify.aria_dialog') }}"
    class="fixed inset-0 z-[100] flex items-end justify-center md:items-start md:pt-[12vh]"
>
    {{-- Scrim --}}
    <div
        x-show="open"
        x-transition:enter="motion-safe:transition motion-safe:duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="motion-safe:transition motion-safe:duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="{{ $scrimClass }}"
        aria-hidden="true"
    ></div>

    {{-- Panel --}}
    {{--
        NOTE: @alpinejs/focus is NOT registered in this project.
        x-trap.noscroll.inert="open" is therefore OMITTED here.
        Focus management relies on the existing listener in modal.blade.php
        which listens for `scoutify:opened` and focuses [data-focus="gs-input"].
        Click-outside is handled by the scrim's @click="open = false".
    --}}
    <div
        x-show="open"
        x-transition:enter="motion-safe:transition motion-safe:duration-150 motion-safe:ease-out"
        x-transition:enter-start="opacity-0 translate-y-2 md:translate-y-0 md:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
        x-transition:leave="motion-safe:transition motion-safe:duration-100 motion-safe:ease-in"
        x-transition:leave-start="opacity-100 translate-y-0 md:scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 md:translate-y-0 md:scale-95"
        data-scoutify-dialog
        class="{{ $panelClass }}"
    >
        {{ $slot }}
    </div>
</div>
