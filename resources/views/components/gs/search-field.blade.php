@props([
    'wireModel' => null,
    'placeholder' => __('scoutify::scoutify.search_placeholder'),
    'autofocus' => false,
    'controlsId' => null,
    'activedescendantExpr' => null,
    'dataFocus' => 'gs-input',
])

<div class="relative" x-data="{ composing: false }">
    <x-scoutify::gs.input
        :wire-model="$wireModel"
        :icon="'magnifying-glass'"
        clearable
        :data-focus="$dataFocus"
        type="search"
        :placeholder="$placeholder"
        autocomplete="off"
        :autofocus="$autofocus"
        role="combobox"
        aria-expanded="true"
        :aria-controls="$controlsId"
        :aria-activedescendant="$activedescendantExpr"
        :aria-label="$placeholder"
        @compositionstart="composing = true"
        @compositionend="composing = false"
        @keydown.backspace="if ($wire.query === '') $wire.call('clearFilters')"
        {{ $attributes }}
    />

    {{-- Spinner inline (sobre o botão clear) --}}
    <div
        wire:loading.delay.long
        @if ($wireModel) wire:target="{{ $wireModel }}" @endif
        class="pointer-events-none absolute inset-y-0 right-9 flex items-center"
        aria-hidden="true"
    >
        <svg class="size-4 motion-safe:animate-spin text-zinc-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
        </svg>
    </div>
</div>
