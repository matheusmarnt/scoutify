<div
    x-data="scoutifyModal()"
    @keydown.window.prevent.ctrl.k="$dispatch('scoutify:open')"
    @keydown.window.prevent.cmd.k="$dispatch('scoutify:open')"
    @keydown.window="if (event.key === '/' && !['INPUT','TEXTAREA','SELECT'].includes(document.activeElement?.tagName) && !document.activeElement?.isContentEditable) { event.preventDefault(); $dispatch('scoutify:open') }"
    @keydown.arrow-down.window.prevent="if (isOpen && isFocusInside()) nav(1)"
    @keydown.arrow-up.window.prevent="if (isOpen && isFocusInside()) nav(-1)"
    @keydown.home.window.prevent="if (isOpen && isFocusInside()) navHome()"
    @keydown.end.window.prevent="if (isOpen && isFocusInside()) navEnd()"
    @keydown.page-down.window.prevent="if (isOpen && isFocusInside()) navPageDown()"
    @keydown.page-up.window.prevent="if (isOpen && isFocusInside()) navPageUp()"
    @keydown.enter.window.prevent="if (isOpen && isFocusInside()) allResults[activeIdx]?.click()"
>
    <x-scoutify::gs.shell wire="isOpen" id="scoutify-search">
        {{-- Header: search input --}}
        <div class="relative shrink-0 border-b border-zinc-100 px-2.5 py-2 dark:border-zinc-800">
            <x-scoutify::gs.search-field
                wire-model="query"
                :placeholder="__('scoutify::scoutify.search_placeholder')"
                autofocus
                controls-id="scoutify-listbox"
                activedescendant-expr="'scoutify-result-' + activeIdx"
            />
        </div>

        {{-- Filters row --}}
        @if (count($this->availableTypes) > 1 || filled($this->query))
            <x-scoutify::gs.filter-row
                wire:key="scoutify-filter-row"
                :types="$this->availableTypes"
                :active-keys="$activeTypes"
                toggle-action="toggleType"
            >
                <x-slot name="trailing">
                    <x-scoutify::gs.toggle wire:model.live="onlyActive" :label="__('scoutify::scoutify.only_active')" />
                    <x-scoutify::gs.toggle wire:model.live="includeTrashed" :label="__('scoutify::scoutify.include_trashed')" />
                </x-slot>
            </x-scoutify::gs.filter-row>
        @endif

        {{-- Live region for screen readers --}}
        <div class="sr-only" role="status" aria-live="polite">
            @if (filled($this->query))
                {{ trans_choice('scoutify::scoutify.results_count', $this->resultCount, ['count' => $this->resultCount]) }}
            @endif
        </div>

        {{-- Body --}}
        <x-scoutify::gs.results-listbox id="scoutify-listbox">
            {{-- Skeleton during loading --}}
            <div wire:loading.delay.long wire:target="query,toggleType,onlyActive,includeTrashed">
                <x-scoutify::gs.skeleton-list :count="5" />
            </div>

            <div wire:loading.remove.delay.long wire:target="query,toggleType,onlyActive,includeTrashed">
                @if (blank($this->query))
                    <x-scoutify::gs.idle-state />
                @elseif (empty($this->results))
                    <x-scoutify::gs.empty-state />
                @else
                    @php $globalIdx = 0; @endphp
                    @foreach (collect($this->results)->groupBy('group') as $groupKey => $groupResults)
                        @php
                            $firstResult = $groupResults->first();
                            $groupLabel  = $firstResult['groupLabel'] ?? $groupKey;
                            $groupIcon   = $firstResult['icon'] ?? 'heroicon-o-magnifying-glass';
                            $groupTotal  = $groupResults->count();
                        @endphp
                        <div class="px-2 pt-3 pb-1 not-first:border-t not-first:border-dashed not-first:border-zinc-100 dark:not-first:border-zinc-800">
                            <x-scoutify::gs.group-header
                                :icon="$groupIcon"
                                :label="$groupLabel"
                                :total="$groupTotal"
                                :color="$firstResult['groupColor'] ?? 'zinc'"
                            />

                            @foreach ($groupResults as $result)
                                @php $idx = $globalIdx++; @endphp
                                <x-scoutify::gs.result-row
                                    :id="'scoutify-result-'.$idx"
                                    :url="$result['url']"
                                    :icon="$result['icon']"
                                    :group-color="$result['groupColor'] ?? 'zinc'"
                                    :title-html="$result['titleHtml']"
                                    :subtitle-html="$result['subtitleHtml']"
                                    :index="$idx"
                                    :remember-query="$this->query"
                                />
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        </x-scoutify::gs.results-listbox>

        {{-- Footer --}}
        <x-scoutify::gs.hint-bar />
    </x-scoutify::gs.shell>
</div>

@script
<script>
    Alpine.data('scoutifyModal', () => ({
        triggerEl: null,
        activeIdx: 0,

        get allResults() {
            return [...document.querySelectorAll('#scoutify-listbox [data-search-result]')];
        },

        nav(delta) {
            const items = this.allResults;
            if (! items.length) return;
            this.activeIdx = ((this.activeIdx + delta) % items.length + items.length) % items.length;
            items[this.activeIdx]?.scrollIntoView({ block: 'nearest' });
        },

        navHome() {
            const i = this.allResults;
            if (i.length) { this.activeIdx = 0; i[0]?.scrollIntoView({ block: 'nearest' }); }
        },

        navEnd() {
            const i = this.allResults;
            if (i.length) { this.activeIdx = i.length - 1; i[this.activeIdx]?.scrollIntoView({ block: 'nearest' }); }
        },

        navPageUp() {
            const i = this.allResults;
            if (i.length) { this.activeIdx = Math.max(this.activeIdx - 5, 0); i[this.activeIdx]?.scrollIntoView({ block: 'nearest' }); }
        },

        navPageDown() {
            const i = this.allResults;
            if (i.length) { this.activeIdx = Math.min(this.activeIdx + 5, i.length - 1); i[this.activeIdx]?.scrollIntoView({ block: 'nearest' }); }
        },

        isFocusInside() {
            return !! document.activeElement?.closest('[data-scoutify-dialog]');
        },

        init() {
            window.addEventListener('scoutify:open', () => {
                this.$wire.call('open');
            });
            window.addEventListener('scoutify:opened', () => {
                this.triggerEl = document.activeElement;
                this.activeIdx = 0;
                this.$nextTick(() => document.querySelector('[data-focus="gs-input"]')?.focus());
            });
            window.addEventListener('scoutify:closed', () => {
                this.$nextTick(() => this.triggerEl?.focus());
            });

            window.addEventListener('scoutify:remember', (e) => {
                const term = (e?.detail?.term ?? '').toString().trim();
                if (! term) return;
                const key = 'scoutify-recent';
                const list = JSON.parse(localStorage.getItem(key) || '[]');
                const next = [term, ...list.filter(t => t !== term)].slice(0, 8);
                localStorage.setItem(key, JSON.stringify(next));
            });
        },
    }));
</script>
@endscript
