@props(['delay' => '0ms'])

<div class="flex items-center gap-3 rounded-lg px-2.5 py-2.5 motion-safe:animate-pulse" style="animation-delay: {{ $delay }};">
    <div class="size-8 shrink-0 rounded-xl bg-zinc-100 dark:bg-zinc-800"></div>
    <div class="min-w-0 flex-1 space-y-1.5">
        <div class="h-3 w-1/3 rounded bg-zinc-100 dark:bg-zinc-800"></div>
        <div class="h-2 w-2/3 rounded bg-zinc-100/70 dark:bg-zinc-800/70"></div>
    </div>
</div>
