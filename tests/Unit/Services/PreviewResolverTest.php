<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Matheusmarnt\Scoutify\Services\PreviewResolver;
use Matheusmarnt\Scoutify\Support\PreviewDto;

describe('PreviewResolver::pickViewerView', function () {
    beforeEach(function () {
        config(['scoutify.preview.viewer_for_mime' => [
            'application/pdf' => 'scoutify::components.gs.preview.viewer-pdf',
            'image/*' => 'scoutify::components.gs.preview.viewer-image',
            'video/*' => 'scoutify::components.gs.preview.viewer-video',
        ]]);
        config(['scoutify.preview.fallback_view' => 'scoutify::components.gs.preview.viewer-fallback']);
    });

    it('returns exact mime match', function () {
        $resolver = new PreviewResolver;

        expect($resolver->pickViewerView('application/pdf'))
            ->toBe('scoutify::components.gs.preview.viewer-pdf');
    });

    it('returns wildcard match for image types', function () {
        $resolver = new PreviewResolver;

        expect($resolver->pickViewerView('image/png'))
            ->toBe('scoutify::components.gs.preview.viewer-image');

        expect($resolver->pickViewerView('image/webp'))
            ->toBe('scoutify::components.gs.preview.viewer-image');
    });

    it('returns wildcard match for video types', function () {
        $resolver = new PreviewResolver;

        expect($resolver->pickViewerView('video/mp4'))
            ->toBe('scoutify::components.gs.preview.viewer-video');
    });

    it('returns fallback for unknown mime', function () {
        $resolver = new PreviewResolver;

        expect($resolver->pickViewerView('application/vnd.openxmlformats-officedocument.wordprocessingml.document'))
            ->toBe('scoutify::components.gs.preview.viewer-fallback');
    });

    it('dto view override takes precedence', function () {
        $dto = PreviewDto::fromDisk('s3', 'a.pdf', view: 'custom::my-viewer');
        $resolver = new PreviewResolver;

        $view = $dto->view ?? $resolver->pickViewerView('application/pdf');

        expect($view)->toBe('custom::my-viewer');
    });
});

describe('PreviewResolver::resolveMime', function () {
    it('returns explicit mime without storage call', function () {
        $dto = PreviewDto::fromDisk('local', 'file.pdf', mime: 'application/pdf');
        $resolver = new PreviewResolver;

        expect($resolver->resolveMime($dto))->toBe('application/pdf');
    });

    it('returns fallback for external url without mime', function () {
        $dto = PreviewDto::fromUrl('https://example.com/file', mime: 'image/jpeg');
        $resolver = new PreviewResolver;

        expect($resolver->resolveMime($dto))->toBe('image/jpeg');
    });

    it('detects mime from storage disk when not explicit', function () {
        Storage::fake('local');
        Storage::disk('local')->put('doc.pdf', '%PDF-1.4 test content');

        $dto = PreviewDto::fromDisk('local', 'doc.pdf');
        $resolver = new PreviewResolver;

        $mime = $resolver->resolveMime($dto);
        expect($mime)->not->toBe('');
    });

    it('returns octet-stream when storage file does not exist', function () {
        Storage::fake('local');

        $dto = PreviewDto::fromDisk('local', 'nonexistent/missing.pdf');
        $resolver = new PreviewResolver;

        expect($resolver->resolveMime($dto))->toBe('application/octet-stream');
    });

    it('returns octet-stream for external url with unreachable host', function () {
        $dto = PreviewDto::fromUrl('https://0.0.0.0/totally-unreachable.unknown');
        $resolver = new PreviewResolver;

        expect($resolver->resolveMime($dto))->toBe('application/octet-stream');
    });
});

describe('PreviewResolver::resolveStreamUrl', function () {
    it('returns external url directly', function () {
        $dto = PreviewDto::fromUrl('https://cdn.example.com/file.pdf');
        $model = Mockery::mock(Model::class);
        $model->shouldReceive('getKey')->andReturn('1');

        $resolver = new PreviewResolver;

        expect($resolver->resolveStreamUrl($dto, $model))->toBe('https://cdn.example.com/file.pdf');
    });

    it('returns a url for local disk', function () {
        Storage::fake('local');
        $dto = PreviewDto::fromDisk('local', 'reports/file.pdf');
        $model = Mockery::mock(Model::class);
        $model->shouldReceive('getKey')->andReturn('1');

        $resolver = new PreviewResolver;
        $url = $resolver->resolveStreamUrl($dto, $model);

        expect($url)->toStartWith('http');
    });
});

describe('PreviewResolver::resolveDownloadUrl', function () {
    it('returns external url directly', function () {
        $dto = PreviewDto::fromUrl('https://cdn.example.com/file.pdf');
        $model = Mockery::mock(Model::class);
        $model->shouldReceive('getKey')->andReturn('1');

        $resolver = new PreviewResolver;

        expect($resolver->resolveDownloadUrl($dto, $model))->toBe('https://cdn.example.com/file.pdf');
    });

    it('returns a url for local disk', function () {
        Storage::fake('local');
        $dto = PreviewDto::fromDisk('local', 'reports/file.pdf');
        $model = Mockery::mock(Model::class);
        $model->shouldReceive('getKey')->andReturn('1');

        $resolver = new PreviewResolver;
        $url = $resolver->resolveDownloadUrl($dto, $model);

        expect($url)->toStartWith('http');
    });
});
