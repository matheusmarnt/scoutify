<div
    x-data="scoutifyModal()"
    @keydown.window.prevent.ctrl.k="$dispatch('scoutify:open')"
    @keydown.window.prevent.cmd.k="$dispatch('scoutify:open')"
    @keydown.window="if (event.key === '/' && !['INPUT','TEXTAREA','SELECT'].includes(document.activeElement?.tagName) && !document.activeElement?.isContentEditable) { event.preventDefault(); $dispatch('scoutify:open') }"
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
        @if (count($this->availableTypes) > 1 || count($this->results) > 0)
            <x-scoutify::gs.filter-row
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
                            />

                            @foreach ($groupResults as $result)
                                @php $idx = $globalIdx++; @endphp
                                <x-scoutify::gs.result-row
                                    :id="'scoutify-result-'.$idx"
                                    :url="$result['url']"
                                    :icon="$result['icon']"
                                    :title-html="$result['title']"
                                    :subtitle-html="$result['subtitle']"
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

        init() {
            window.addEventListener('scoutify:opened', () => {
                this.triggerEl = document.activeElement;
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
