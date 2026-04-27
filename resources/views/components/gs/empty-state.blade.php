@props([
    'icon' => 'magnifying-glass',
    'title' => __('Nenhum resultado'),
    'description' => __('Tente outros termos ou ajuste os filtros.'),
])

<div class="flex flex-col items-center gap-3 px-6 py-14 text-center">
    <div class="flex size-12 items-center justify-center rounded-2xl bg-zinc-100 motion-safe:animate-pulse dark:bg-zinc-800">
        <x-scoutify::gs.icon :name="$icon" class="size-6 text-zinc-400 dark:text-zinc-500" />
    </div>
    <div>
        <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">{{ $title }}</p>
        <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ $description }}</p>
    </div>
</div>
