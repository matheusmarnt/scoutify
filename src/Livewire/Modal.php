<?php

namespace Matheusmarnt\Scoutify\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Matheusmarnt\Scoutify\Services\SearchAggregator;

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

    public function search(): void
    {
        if (blank($this->query)) {
            $this->results = [];

            return;
        }

        $limit = config('scoutify.modal_per_type', 25);

        $dtos = SearchAggregator::make()->search(
            query: $this->query,
            limit: $limit,
            onlyActive: $this->onlyActive,
            includeTrashed: $this->includeTrashed,
        );

        // Filter by active types if any are toggled
        if (! empty($this->activeTypes)) {
            $dtos = array_filter($dtos, fn ($dto) => in_array($dto->group, $this->activeTypes, true));
            $dtos = array_values($dtos);
        }

        $this->results = array_map(fn ($dto) => $dto->toArray(), $dtos);
    }

    #[Computed]
    public function resultCount(): int
    {
        return count($this->results);
    }

    public function navigateTo(string $url): mixed
    {
        $this->close();

        if (blank($url)) {
            return null;
        }

        return $this->redirect($url, navigate: true);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function availableTypes(): array
    {
        return array_map(
            fn ($key, $meta) => array_merge(['key' => $key], $meta),
            array_keys(config('scoutify.types', [])),
            array_values(config('scoutify.types', [])),
        );
    }

    public function render(): View
    {
        return view('scoutify::livewire.modal');
    }
}
