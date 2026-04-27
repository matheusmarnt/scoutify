@props(['icon', 'label', 'total' => null])

<div class="mb-1 flex items-center gap-1.5 px-2 pb-0.5">
    <x-scoutify::gs.icon :name="$icon" class="size-3 text-zinc-500 dark:text-zinc-400" />
    <span class="text-[10px] font-semibold uppercase tracking-widest text-zinc-500 dark:text-zinc-400">
        {{ $label }}
    </span>
    @if (! is_null($total))
        <x-scoutify::gs.badge :text="(string) $total" class="ml-auto" />
    @endif
</div>
