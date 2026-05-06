<?php

namespace Matheusmarnt\Scoutify\Support;

use Matheusmarnt\Scoutify\Contracts\GloballySearchable;

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
        public string $linkTarget = 'navigate',
        public ?string $modelClass = null,
        public ?PreviewDto $preview = null,
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
            'linkTarget' => $this->linkTarget,
            'modelClass' => $this->modelClass,
            'preview' => $this->preview?->toArray(),
        ];
    }

    public static function fromModel(
        GloballySearchable $model,
        string $url,
        string $groupLabel = '',
        ?string $modelKey = null,
        ?PreviewDto $preview = null,
    ): self {
        return new self(
            title: method_exists($model, 'globalSearchTitle') ? $model->globalSearchTitle() : '',
            subtitle: method_exists($model, 'globalSearchSubtitle') ? $model->globalSearchSubtitle() : null,
            url: $url,
            icon: $model::globalSearchIcon(),
            group: $model::globalSearchGroup(),
            groupLabel: $groupLabel ?: $model::globalSearchGroup(),
            groupColor: $model::globalSearchColor(),
            modelKey: $modelKey,
            linkTarget: method_exists($model, 'globalSearchLinkTarget') ? $model->globalSearchLinkTarget() : 'navigate',
            modelClass: get_class($model),
            preview: $preview,
        );
    }
}
