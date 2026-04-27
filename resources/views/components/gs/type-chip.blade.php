@props(['icon', 'label', 'active' => false])

<button
    type="button"
    aria-pressed="{{ $active ? 'true' : 'false' }}"
    {{ $attributes->merge([
        'class' => 'group inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[11px] font-medium transition focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-accent/40 motion-safe:hover:-translate-y-px',
    ])->class([
        'border-accent/30 bg-accent/10 text-accent dark:border-accent/40 dark:bg-accent/15' => $active,
        'border-zinc-200 bg-white text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-zinc-300' => ! $active,
    ]) }}
>
    <x-scoutify::gs.icon :name="$icon" class="size-3" />
    {{ $label }}
</button>
