@props([
    'text' => null,
])

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full bg-zinc-100 px-1.5 py-0.5 text-[10px] font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400']) }}>
    {{ $text ?? $slot }}
</span>
