@props([
    'wire' => 'isOpen',
    'id' => 'scoutify-search',
    'ariaLabel' => null,
])

<x-scoutify::gs.dialog
    :wire="$wire"
    :id="$id"
    :aria-label="$ariaLabel ?? __('Busca global')"
>
    <div
        class="flex max-h-[90dvh] min-h-0 flex-col overflow-hidden rounded-t-2xl bg-white pb-[env(safe-area-inset-bottom)] md:max-h-[80vh] md:rounded-xl md:shadow-2xl md:ring-1 md:ring-zinc-900/5 dark:bg-zinc-900 dark:md:ring-white/10"
    >
        {{-- Drag handle (mobile bottom-sheet feel) --}}
        <div class="mx-auto mt-2 mb-1 h-1 w-10 shrink-0 rounded-full bg-zinc-200 md:hidden dark:bg-zinc-700" aria-hidden="true"></div>

        {{ $slot }}
    </div>
</x-scoutify::gs.dialog>
