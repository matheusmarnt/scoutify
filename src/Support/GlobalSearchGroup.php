<?php

namespace Matheusmarnt\Scoutify\Support;

use Illuminate\Contracts\Support\Arrayable;

final readonly class GlobalSearchGroup implements Arrayable
{
    /**
     * @param  array<ResultDto>  $results
     */
    public function __construct(
        public string $key,
        public string $label,
        public string $icon,
        public string $color,
        public int $total,
        public array $results,
    ) {}

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'icon' => $this->icon,
            'color' => $this->color,
            'total' => $this->total,
            'results' => array_map(fn (ResultDto $r) => $r->toArray(), $this->results),
        ];
    }
}
