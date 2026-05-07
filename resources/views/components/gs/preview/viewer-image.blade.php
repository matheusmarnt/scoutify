@props(['streamUrl', 'filename' => ''])

<div class="flex h-full items-center justify-center overflow-auto">
    <img
        src="{{ $streamUrl }}"
        alt="{{ $filename }}"
        class="max-h-full max-w-full rounded-lg object-contain"
    />
</div>
