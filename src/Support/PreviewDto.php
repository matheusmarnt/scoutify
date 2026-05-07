<?php

namespace Matheusmarnt\Scoutify\Support;

final readonly class PreviewDto
{
    public function __construct(
        public ?string $disk = null,
        public ?string $path = null,
        public ?string $url = null,
        public ?string $mime = null,
        public ?string $filename = null,
        public ?int $sizeBytes = null,
        public ?string $view = null,
        public ?int $ttl = null,
    ) {
        if ($this->disk !== null && $this->url !== null) {
            throw new \InvalidArgumentException('PreviewDto: disk+path and url are mutually exclusive.');
        }
    }

    public static function fromDisk(
        string $disk,
        string $path,
        ?string $mime = null,
        ?string $filename = null,
        ?int $sizeBytes = null,
        ?string $view = null,
        ?int $ttl = null,
    ): self {
        return new self(
            disk: $disk,
            path: $path,
            mime: $mime,
            filename: $filename ?? basename($path),
            sizeBytes: $sizeBytes,
            view: $view,
            ttl: $ttl,
        );
    }

    public static function fromUrl(
        string $url,
        ?string $mime = null,
        ?string $filename = null,
        ?int $sizeBytes = null,
        ?string $view = null,
        ?int $ttl = null,
    ): self {
        return new self(
            url: $url,
            mime: $mime,
            filename: $filename ?? basename(parse_url($url, PHP_URL_PATH) ?? 'file'),
            sizeBytes: $sizeBytes,
            view: $view,
            ttl: $ttl,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            disk: $data['disk'] ?? null,
            path: $data['path'] ?? null,
            url: $data['url'] ?? null,
            mime: $data['mime'] ?? null,
            filename: $data['filename'] ?? null,
            sizeBytes: $data['sizeBytes'] ?? null,
            view: $data['view'] ?? null,
            ttl: $data['ttl'] ?? null,
        );
    }

    public function isStorageBased(): bool
    {
        return $this->disk !== null && $this->path !== null;
    }

    public function isExternalUrl(): bool
    {
        return $this->url !== null;
    }

    public function toArray(): array
    {
        return [
            'disk' => $this->disk,
            'path' => $this->path,
            'url' => $this->url,
            'mime' => $this->mime,
            'filename' => $this->filename,
            'sizeBytes' => $this->sizeBytes,
            'view' => $this->view,
            'ttl' => $this->ttl,
        ];
    }
}
