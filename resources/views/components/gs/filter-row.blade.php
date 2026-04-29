@props(['types' => [], 'activeKeys' => [], 'toggleAction' => 'toggleType'])

<div class="shrink-0 border-b border-zinc-100 bg-zinc-50/60 dark:border-zinc-800 dark:bg-zinc-900/40">
    {{-- Chips row --}}
    <div class="flex flex-wrap items-center gap-1.5 px-3.5 py-2">
        <x-scoutify::gs.type-chip-group
            :types="$types"
            :active-keys="$activeKeys"
            :toggle-action="$toggleAction"
        />
    </div>

    {{-- Toggles row: fixed below chips, right-aligned, separated by border --}}
    @isset($trailing)
        <div class="flex items-center justify-end gap-5 border-t border-zinc-100 px-3.5 py-1.5 dark:border-zinc-800">
            {{ $trailing }}
        </div>
    @endisset
</div>
