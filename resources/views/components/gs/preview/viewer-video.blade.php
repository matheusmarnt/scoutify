@props(['streamUrl', 'filename' => ''])

<div class="flex h-full items-center justify-center">
    <video
        controls
        preload="metadata"
        class="max-h-full max-w-full rounded-lg"
    >
        <source src="{{ $streamUrl }}" />
        {{ __('scoutify::scoutify.video_unsupported') }}
    </video>
</div>
