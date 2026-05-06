<?php

namespace Matheusmarnt\Scoutify\Http\Controllers;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Matheusmarnt\Scoutify\Authorization\GlobalSearchAuthorizer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PreviewStreamController
{
    public function stream(Request $request): StreamedResponse
    {
        abort_unless($request->hasValidSignature(), 403);

        [$disk, $path, $modelClass, $modelKey] = $this->extractParams($request);

        $record = $modelClass::findOrFail($modelKey);
        abort_unless(app(GlobalSearchAuthorizer::class)->authorize($record, $request->user()), 403);

        $storage = Storage::disk($disk);
        if (! $storage instanceof FilesystemAdapter) {
            throw new \RuntimeException('Disk does not use a local filesystem adapter.');
        }

        $mime = $storage->mimeType($path) ?: 'application/octet-stream';
        abort_unless(in_array($mime, config('scoutify.preview.allowed_mimes', []), true), 415);

        abort_if($storage->size($path) > config('scoutify.preview.max_size_bytes', 52428800), 413);

        $filename = rawurlencode(basename($path));

        return $storage->response($path, basename($path), [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=0, no-store',
        ]);
    }

    public function download(Request $request): StreamedResponse
    {
        abort_unless($request->hasValidSignature(), 403);

        [$disk, $path, $modelClass, $modelKey] = $this->extractParams($request);

        $record = $modelClass::findOrFail($modelKey);
        abort_unless(app(GlobalSearchAuthorizer::class)->authorize($record, $request->user()), 403);

        $storage = Storage::disk($disk);
        if (! $storage instanceof FilesystemAdapter) {
            throw new \RuntimeException('Disk does not use a local filesystem adapter.');
        }

        $mime = $storage->mimeType($path) ?: 'application/octet-stream';
        abort_unless(in_array($mime, config('scoutify.preview.allowed_mimes', []), true), 415);

        abort_if($storage->size($path) > config('scoutify.preview.max_size_bytes', 52428800), 413);

        return $storage->download($path, basename($path), [
            'Content-Type' => $mime,
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=0, no-store',
        ]);
    }

    /** @return array{string, string, class-string, string} */
    private function extractParams(Request $request): array
    {
        return [
            (string) $request->query('disk'),
            (string) $request->query('path'),
            (string) $request->query('model_class'),
            (string) $request->query('model_key'),
        ];
    }
}
