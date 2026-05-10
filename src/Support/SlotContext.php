<?php

namespace Matheusmarnt\Scoutify\Support;

use Illuminate\Support\Collection;
use Matheusmarnt\Scoutify\Livewire\Modal;

final readonly class SlotContext
{
    public function __construct(
        public Modal $wire,
        public string $query,
        public Collection $results,
        public bool $hasResults,
        public bool $isIdle,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'wire' => $this->wire,
            'query' => $this->query,
            'results' => $this->results,
            'hasResults' => $this->hasResults,
            'isIdle' => $this->isIdle,
        ];
    }
}
