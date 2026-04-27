@props(['types' => [], 'activeKeys' => [], 'toggleAction' => 'toggleType'])

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-1.5']) }}>
    <span class="text-[11px] font-medium text-zinc-500 dark:text-zinc-400">{{ __('Tipo:') }}</span>

    @foreach ($types as $type)
        <x-scoutify::gs.type-chip
            :icon="$type['icon']"
            :label="$type['label']"
            :active="in_array($type['key'], $activeKeys, true)"
            wire:click="{{ $toggleAction }}('{{ $type['key'] }}')"
        />
    @endforeach

    @isset($trailing)
        <div class="ml-auto flex items-center gap-3">
            {{ $trailing }}
        </div>
    @endisset
</div>
