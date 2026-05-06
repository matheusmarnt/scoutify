<?php

namespace Matheusmarnt\Scoutify\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Matheusmarnt\Scoutify\Support\PreviewDto;

final class PreviewResolver
{
    public function resolveStreamUrl(PreviewDto $dto, Model $record): string
    {
        if ($dto->isExternalUrl()) {
            return $dto->url;
        }

        $expires = now()->addSeconds($dto->ttl ?? config('scoutify.preview.ttl_seconds', 300));
        $filename = rawurlencode($dto->filename ?? basename($dto->path));

        if (Storage::disk($dto->disk)->providesTemporaryUrls()) {
            return Storage::disk($dto->disk)->temporaryUrl(
                $dto->path,
                $expires,
                ['ResponseContentDisposition' => 'inline; filename="' . $filename . '"'],
            );
        }

        return URL::signedRoute('scoutify.preview.stream', [
            'disk' => $dto->disk,
            'path' => $dto->path,
            'model_class' => get_class($record),
            'model_key' => $record->getKey(),
        ], $expires);
    }

    public function resolveDownloadUrl(PreviewDto $dto, Model $record): string
    {
        if ($dto->isExternalUrl()) {
            return $dto->url;
        }

        $expires = now()->addSeconds($dto->ttl ?? config('scoutify.preview.ttl_seconds', 300));
        $filename = rawurlencode($dto->filename ?? basename($dto->path));

        if (Storage::disk($dto->disk)->providesTemporaryUrls()) {
            return Storage::disk($dto->disk)->temporaryUrl(
                $dto->path,
                $expires,
                ['ResponseContentDisposition' => 'attachment; filename="' . $filename . '"'],
            );
        }

        return URL::signedRoute('scoutify.preview.download', [
            'disk' => $dto->disk,
            'path' => $dto->path,
            'model_class' => get_class($record),
            'model_key' => $record->getKey(),
        ], $expires);
    }

    public function resolveMime(PreviewDto $dto): string
    {
        if ($dto->mime !== null) {
            return $dto->mime;
        }

        if ($dto->isStorageBased()) {
            try {
                $mime = Storage::disk($dto->disk)->mimeType($dto->path);
                if ($mime !== false) {
                    return $mime;
                }
            } catch (\Throwable) {
            }
        }

        if ($dto->isExternalUrl()) {
            try {
                $mime = @mime_content_type($dto->url);
                if ($mime !== false) {
                    return $mime;
                }
            } catch (\Throwable) {
            }
        }

        return 'application/octet-stream';
    }

    public function pickViewerView(string $mime): string
    {
        $map = config('scoutify.preview.viewer_for_mime', []);

        if (isset($map[$mime])) {
            return $map[$mime];
        }

        [$type] = explode('/', $mime, 2);
        $wildcard = $type . '/*';
        if (isset($map[$wildcard])) {
            return $map[$wildcard];
        }

        return config('scoutify.preview.fallback_view', 'scoutify::components.gs.preview.viewer-fallback');
    }
}
