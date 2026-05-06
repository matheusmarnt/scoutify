@props(['streamUrl', 'filename' => ''])

<iframe
    src="{{ $streamUrl }}"
    title="{{ $filename }}"
    class="h-full w-full rounded-lg border-0"
    aria-label="{{ $filename }}"
></iframe>
