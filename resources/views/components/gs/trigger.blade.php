@props(['label' => true])

<button
    type="button"
    x-data
    @click="$dispatch('scoutify:open')"
    aria-label="{{ __('Abrir busca global (Ctrl+K)') }}"
    {{ $attributes->merge([
        'class' => 'group inline-flex h-9 min-w-16 items-center gap-2 rounded-lg border border-zinc-200 bg-white px-3 text-sm text-zinc-500 transition hover:border-zinc-300 hover:text-zinc-700 focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-accent/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-zinc-200',
    ]) }}
>
    <x-scoutify::gs.icon name="magnifying-glass" class="size-4 shrink-0" />
    @if ($label)
        <span class="hidden lg:inline">{{ __('Buscar…') }}</span>
    @endif
    <x-scoutify::gs.kbd class="ml-auto hidden lg:inline-flex">⌘K</x-scoutify::gs.kbd>
</button>
