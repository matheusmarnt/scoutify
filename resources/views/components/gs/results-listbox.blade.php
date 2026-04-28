@props(['id' => 'gs-listbox', 'ariaLabel' => null])

<div
    id="{{ $id }}"
    role="listbox"
    aria-label="{{ $ariaLabel ?? __('scoutify::scoutify.results_listbox_label') }}"
    class="min-h-0 flex-1 overflow-y-auto"
>
    {{ $slot }}
</div>
