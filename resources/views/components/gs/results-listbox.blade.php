@props(['id' => 'gs-listbox', 'ariaLabel' => null])

<div
    x-data="{
        activeIdx: 0,
        get allResults() { return [...$el.querySelectorAll('[data-search-result]')]; }
    }"
    x-effect="activeIdx = allResults.length ? Math.min(activeIdx, allResults.length - 1) : 0"
    @keydown.arrow-down.prevent="
        const i = allResults; if (! i.length) return;
        activeIdx = (activeIdx + 1) % i.length;
        i[activeIdx]?.scrollIntoView({ block: 'nearest' });
    "
    @keydown.arrow-up.prevent="
        const i = allResults; if (! i.length) return;
        activeIdx = (activeIdx - 1 + i.length) % i.length;
        i[activeIdx]?.scrollIntoView({ block: 'nearest' });
    "
    @keydown.enter.prevent="allResults[activeIdx]?.click()"
    @keydown.home.prevent="
        if (! allResults.length) return;
        activeIdx = 0;
        allResults[0]?.scrollIntoView({ block: 'nearest' });
    "
    @keydown.end.prevent="
        if (! allResults.length) return;
        activeIdx = allResults.length - 1;
        allResults[activeIdx]?.scrollIntoView({ block: 'nearest' });
    "
    @keydown.page-down.prevent="
        if (! allResults.length) return;
        activeIdx = Math.min(activeIdx + 5, allResults.length - 1);
        allResults[activeIdx]?.scrollIntoView({ block: 'nearest' });
    "
    @keydown.page-up.prevent="
        if (! allResults.length) return;
        activeIdx = Math.max(activeIdx - 5, 0);
        allResults[activeIdx]?.scrollIntoView({ block: 'nearest' });
    "
    id="{{ $id }}"
    role="listbox"
    aria-label="{{ $ariaLabel ?? __('Resultados da busca') }}"
    class="min-h-0 flex-1 overflow-y-auto"
>
    {{ $slot }}
</div>
