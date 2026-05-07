@props(['preview'])

@php
    $filename   = $preview['filename'] ?? '';
    $streamUrl  = $preview['stream_url'] ?? null;
    $downloadUrl = $preview['download_url'] ?? null;
    $viewerView = $preview['viewer_view'] ?? 'scoutify::components.gs.preview.viewer-fallback';
    $mime       = $preview['resolved_mime'] ?? '';
    $sizeBytes  = $preview['sizeBytes'] ?? null;
    $isExternal = !empty($preview['url']);
@endphp

<div class="flex h-full flex-col {{ config('scoutify.classes.preview.pane', '') }}">
    <x-scoutify::gs.preview.header
        :filename="$filename"
        :download-url="$downloadUrl"
        :is-external="$isExternal"
    />

    <div class="relative min-h-0 flex-1 overflow-auto p-3 {{ config('scoutify.classes.preview.body', '') }}">
        @if ($streamUrl || $downloadUrl)
            @include($viewerView, [
                'streamUrl'   => $streamUrl,
                'downloadUrl' => $downloadUrl ?? $streamUrl,
                'filename'    => $filename,
                'mime'        => $mime,
                'sizeBytes'   => $sizeBytes,
            ])
        @else
            <x-scoutify::gs.empty-state />
        @endif
    </div>
</div>
