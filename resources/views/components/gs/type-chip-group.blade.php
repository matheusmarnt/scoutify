@props(['types' => [], 'activeKeys' => [], 'toggleAction' => 'toggleType'])

@php
    $visibleLimit    = 5;
    $visibleTypes    = array_slice($types, 0, $visibleLimit);
    $hiddenTypes     = array_slice($types, $visibleLimit);
    $hiddenCount     = count($hiddenTypes);
    $hasActiveHidden = $hiddenCount > 0 && count(
        array_filter($hiddenTypes, fn ($t) => in_array($t['key'], $activeKeys, true))
    ) > 0;
@endphp

<div
    x-data="{ expanded: @js($hasActiveHidden) }"
    {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-1.5']) }}
>
    <span class="text-xs font-semibold tracking-wide text-zinc-700 uppercase dark:text-zinc-200">
        {{ __('Tipo:') }}
    </span>

    {{-- Always-visible chips --}}
    @foreach ($visibleTypes as $type)
        <x-scoutify::gs.type-chip
            :icon="$type['icon']"
            :label="$type['label']"
            :active="in_array($type['key'], $activeKeys, true)"
            wire:click="{{ $toggleAction }}('{{ $type['key'] }}')"
        />
    @endforeach

    {{-- Collapsible extra chips — each span is a flex item, hidden via x-show --}}
    @foreach ($hiddenTypes as $type)
        <span
            x-cloak
            x-show="expanded"
            x-transition:enter="motion-safe:transition motion-safe:duration-150 motion-safe:ease-out"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="motion-safe:transition motion-safe:duration-100 motion-safe:ease-in"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="inline-flex origin-left"
        >
            <x-scoutify::gs.type-chip
                :icon="$type['icon']"
                :label="$type['label']"
                :active="in_array($type['key'], $activeKeys, true)"
                wire:click="{{ $toggleAction }}('{{ $type['key'] }}')"
            />
        </span>
    @endforeach

    {{-- Expand / collapse toggle --}}
    @if ($hiddenCount > 0)
        <button
            type="button"
            @click="expanded = !expanded"
            :aria-expanded="expanded ? 'true' : 'false'"
            aria-label="{{ __('Mostrar mais tipos de filtro') }}"
            class="group inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[11px] font-medium transition-colors focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-scoutify-accent/40 motion-safe:transition-colors"
            :class="expanded
                ? 'border-zinc-300 bg-white text-zinc-600 hover:border-zinc-400 hover:text-zinc-700 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:border-zinc-500 dark:hover:text-zinc-200'
                : 'border-dashed border-zinc-300 bg-white text-zinc-500 hover:border-zinc-400 hover:text-zinc-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:border-zinc-500 dark:hover:text-zinc-200'"
        >
            {{-- Collapsed: "+N" with optional active indicator --}}
            <span x-show="!expanded" class="flex items-center gap-1">
                +{{ $hiddenCount }}
                @if ($hasActiveHidden)
                    <span class="size-1.5 rounded-full bg-scoutify-accent" aria-hidden="true"></span>
                @endif
            </span>
            {{-- Expanded: chevron-up --}}
            <span x-cloak x-show="expanded" class="flex items-center">
                <x-scoutify::gs.icon name="chevron-up" class="size-3" />
            </span>
        </button>
    @endif

    @isset($trailing)
        <div class="ml-auto flex items-center gap-3">
            {{ $trailing }}
        </div>
    @endisset
</div>
