@props(['filename' => '', 'downloadUrl' => null, 'isExternal' => false])

@php $theme = app(\Matheusmarnt\Scoutify\ScoutifyManager::class)->theme(); @endphp

<div class="flex shrink-0 items-center gap-2 border-b border-zinc-100 px-2.5 py-2 dark:border-zinc-800 {{ config('scoutify.classes.preview.header', '') }}">
    <button
        type="button"
        wire:click="closePreview"
        class="inline-flex size-8 shrink-0 items-center justify-center rounded-lg text-zinc-500 transition hover:bg-zinc-100 hover:text-zinc-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/40 dark:hover:bg-zinc-800 dark:hover:text-zinc-200 {{ $theme->getPreviewBackButton() ?? '' }}"
        aria-label="{{ __('scoutify::scoutify.back') }}"
        data-preview-back-btn
        x-ref="previewBackBtn"
    >
        <x-scoutify::gs.icon name="heroicon-o-arrow-left" class="size-4" />
    </button>

    <p
        class="min-w-0 flex-1 truncate text-sm font-medium text-zinc-900 dark:text-zinc-100"
        aria-live="polite"
    >{{ $filename }}</p>

    @if ($downloadUrl)
        <a
            href="{{ $downloadUrl }}"
            @if ($isExternal) target="_blank" rel="noopener noreferrer" @else download @endif
            class="inline-flex size-8 shrink-0 items-center justify-center rounded-lg text-zinc-500 transition hover:bg-zinc-100 hover:text-zinc-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/40 dark:hover:bg-zinc-800 dark:hover:text-zinc-200 {{ $theme->getDownloadButton() ?? '' }}"
            aria-label="{{ __('scoutify::scoutify.download') }}"
        >
            <x-scoutify::gs.icon name="heroicon-o-arrow-down-tray" class="size-4" />
        </a>
    @endif
</div>
