<?php

namespace Matheusmarnt\Scoutify\Support;

final readonly class ResultDto
{
    public function __construct(
        public string $title,
        public ?string $subtitle,
        public string $url,
        public string $icon,
        public string $group,
        public string $groupLabel,
        public string $groupColor,
        public ?string $modelKey = null,
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'url' => $this->url,
            'icon' => $this->icon,
            'group' => $this->group,
            'groupLabel' => $this->groupLabel,
            'groupColor' => $this->groupColor,
            'modelKey' => $this->modelKey,
        ];
    }
}
