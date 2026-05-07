@props(['downloadUrl', 'filename' => '', 'sizeBytes' => null, 'mime' => ''])

<div class="flex h-full flex-col items-center justify-center gap-4 px-6 text-center">
    <x-scoutify::gs.icon
        name="heroicon-o-document"
        class="size-12 text-zinc-400 dark:text-zinc-500"
    />

    <div class="space-y-1">
        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $filename }}</p>
        @if ($sizeBytes)
            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                {{ number_format($sizeBytes / 1048576, 1) }} MB
            </p>
        @endif
        @if ($mime)
            <p class="text-xs text-zinc-400 dark:text-zinc-500">{{ $mime }}</p>
        @endif
    </div>

    <a
        href="{{ $downloadUrl }}"
        download
        class="inline-flex items-center gap-2 rounded-lg bg-accent px-4 py-2 text-sm font-medium text-white transition hover:bg-accent/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/50"
    >
        <x-scoutify::gs.icon name="heroicon-o-arrow-down-tray" class="size-4" />
        {{ __('scoutify::scoutify.download') }}
    </a>
</div>
