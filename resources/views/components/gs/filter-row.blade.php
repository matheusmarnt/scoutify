@props(['types' => [], 'activeKeys' => [], 'toggleAction' => 'toggleType'])

<div class="flex shrink-0 flex-wrap items-center gap-1.5 border-b border-zinc-100 bg-zinc-50/60 px-3.5 py-2 dark:border-zinc-800 dark:bg-zinc-900/40">
    <x-scoutify::gs.type-chip-group
        :types="$types"
        :active-keys="$activeKeys"
        :toggle-action="$toggleAction"
    >
        <x-slot name="trailing">
            {{ $trailing ?? '' }}
        </x-slot>
    </x-scoutify::gs.type-chip-group>
</div>
