<?php

namespace Matheusmarnt\Scoutify\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Matheusmarnt\Scoutify\Services\SearchAggregator;
use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;
use Matheusmarnt\Scoutify\Support\Highlighter;

class Modal extends Component
{
    public bool $isOpen = false;

    public string $query = '';

    /** @var array<string> */
    public array $activeTypes = [];

    public bool $includeTrashed = false;

    public bool $onlyActive = false;

    public int $activeIndex = 0;

    /**
     * Flat list of ResultDto-shaped arrays, grouped by group key.
     *
     * @var array<int, array<string, mixed>>
     */
    public array $results = [];

    #[On('scoutify:open')]
    public function open(?string $preset = null): void
    {
        $this->isOpen = true;
        $this->activeIndex = 0;
        $this->dispatch('scoutify:opened');

        if ($preset !== null) {
            $this->query = $preset;
            $this->search();
        }
    }

    public function close(): void
    {
        $this->isOpen = false;
        $this->reset(['query', 'results', 'activeIndex', 'activeTypes', 'includeTrashed', 'onlyActive']);
        $this->dispatch('scoutify:closed');
    }

    public function updatedQuery(): void
    {
        $this->activeIndex = 0;
        $this->search();
    }

    public function updatedIncludeTrashed(): void
    {
        $this->activeIndex = 0;
        $this->search();
    }

    public function updatedOnlyActive(): void
    {
        $this->activeIndex = 0;
        $this->search();
    }

    public function updatedActiveTypes(): void
    {
        $this->activeIndex = 0;
        $this->search();
    }

    public function toggleType(string $key): void
    {
        if (in_array($key, $this->activeTypes, true)) {
            $this->activeTypes = array_values(array_filter($this->activeTypes, fn ($t) => $t !== $key));
        } else {
            $this->activeTypes[] = $key;
        }

        $this->activeIndex = 0;
        $this->search();
    }

    public function clearFilters(): void
    {
        $this->activeTypes = [];
        $this->includeTrashed = false;
        $this->onlyActive = false;
        $this->activeIndex = 0;
        $this->search();
    }

    public function search(): void
    {
        if (blank($this->query)) {
            $this->results = [];

            return;
        }

        $limit = config('scoutify.modal_per_type', 25);

        $groups = SearchAggregator::make()->search(
            query: $this->query,
            limit: $limit,
            onlyActive: $this->onlyActive,
            includeTrashed: $this->includeTrashed,
        );

        // Flatten groups into a single ResultDto list
        $dtos = $groups->flatMap(fn ($group) => $group->results)->values();

        // Filter by active types if any are toggled
        if (! empty($this->activeTypes)) {
            $dtos = $dtos->filter(fn ($dto) => in_array($dto->group, $this->activeTypes, true))->values();
        }

        $highlighter = app(Highlighter::class);
        $this->results = $dtos->map(
            fn ($dto) => $dto->toArray() + [
                'titleHtml' => (string) $highlighter->highlight($dto->title, $this->query),
                'subtitleHtml' => (string) $highlighter->highlight($dto->subtitle, $this->query),
            ],
        )->all();
    }

    #[Computed]
    public function resultCount(): int
    {
        return count($this->results);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function availableTypes(): array
    {
        $registryTypes = app()->bound(GlobalSearchRegistry::class)
            ? app(GlobalSearchRegistry::class)->all()
            : [];

        $configTypes = config('scoutify.types', []);

        // Merge: registry base, config overrides per-key metadata
        $merged = $registryTypes;
        foreach ($configTypes as $class => $meta) {
            $merged[$class] = array_merge($merged[$class] ?? [], $meta);
        }

        return array_values(array_map(
            fn ($class, $meta) => array_merge(['key' => $meta['key'] ?? $class], $meta),
            array_keys($merged),
            array_values($merged),
        ));
    }

    public function render(): View
    {
        return view('scoutify::livewire.modal');
    }
}
