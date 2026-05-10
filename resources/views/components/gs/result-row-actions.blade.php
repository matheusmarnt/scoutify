@props(['result'])

@php
    $theme      = app(\Matheusmarnt\Scoutify\ScoutifyManager::class)->theme();
    $preview    = $result['preview'] ?? null;
    $modelKey   = $result['modelKey'] ?? null;
    $hasPreview = !empty($preview) && $modelKey !== null;
    $canView    = $hasPreview && !in_array($preview['mime'] ?? '', ['', null], true)
        ? true
        : $hasPreview;
@endphp

@if ($hasPreview)
    @php
        $isPdf   = str_starts_with($preview['mime'] ?? '', 'application/pdf');
        $isImage = str_starts_with($preview['mime'] ?? '', 'image/');
        $isVideo = str_starts_with($preview['mime'] ?? '', 'video/');
        $hasViewer = $isPdf || $isImage || $isVideo || empty($preview['mime']);
    @endphp

    @if ($hasViewer)
        <button
            type="button"
            wire:click="openPreview(@js($modelKey))"
            data-result-action="preview"
            data-preview-btn="{{ $modelKey }}"
            class="inline-flex size-7 shrink-0 items-center justify-center rounded-md text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/40 dark:hover:bg-zinc-800 dark:hover:text-zinc-300 {{ $theme->getPreviewButton() ?? '' }}"
            aria-label="{{ __('scoutify::scoutify.preview') }}"
        >
            <x-scoutify::gs.icon name="heroicon-o-eye" class="size-3.5" />
        </button>
    @endif

    <button
        type="button"
        wire:click="downloadPreview(@js($modelKey))"
        data-result-action="download"
        class="inline-flex size-7 shrink-0 items-center justify-center rounded-md text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/40 dark:hover:bg-zinc-800 dark:hover:text-zinc-300 {{ $theme->getDownloadButton() ?? '' }}"
        aria-label="{{ __('scoutify::scoutify.download') }}"
    >
        <x-scoutify::gs.icon name="heroicon-o-arrow-down-tray" class="size-3.5" />
    </button>
@endif
