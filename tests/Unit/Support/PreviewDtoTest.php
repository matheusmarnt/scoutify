<?php

use Matheusmarnt\Scoutify\Support\PreviewDto;

describe('PreviewDto', function () {
    it('constructs from disk', function () {
        $dto = PreviewDto::fromDisk('s3', 'reports/q4.pdf', filename: 'Q4.pdf', sizeBytes: 1024);

        expect($dto->disk)->toBe('s3')
            ->and($dto->path)->toBe('reports/q4.pdf')
            ->and($dto->filename)->toBe('Q4.pdf')
            ->and($dto->sizeBytes)->toBe(1024)
            ->and($dto->url)->toBeNull()
            ->and($dto->isStorageBased())->toBeTrue()
            ->and($dto->isExternalUrl())->toBeFalse();
    });

    it('defaults filename to basename when not provided', function () {
        $dto = PreviewDto::fromDisk('local', 'uploads/doc.pdf');

        expect($dto->filename)->toBe('doc.pdf');
    });

    it('constructs from external url', function () {
        $dto = PreviewDto::fromUrl('https://cdn.example.com/files/report.pdf');

        expect($dto->url)->toBe('https://cdn.example.com/files/report.pdf')
            ->and($dto->disk)->toBeNull()
            ->and($dto->isExternalUrl())->toBeTrue()
            ->and($dto->isStorageBased())->toBeFalse();
    });

    it('defaults filename to url basename', function () {
        $dto = PreviewDto::fromUrl('https://cdn.example.com/files/report.pdf');

        expect($dto->filename)->toBe('report.pdf');
    });

    it('throws when disk and url both provided', function () {
        expect(fn () => new PreviewDto(disk: 's3', path: 'a.pdf', url: 'https://x.com/a.pdf'))
            ->toThrow(\InvalidArgumentException::class);
    });

    it('serializes to array and back', function () {
        $original = PreviewDto::fromDisk('public', 'img/logo.png', mime: 'image/png', sizeBytes: 512);
        $dto = PreviewDto::fromArray($original->toArray());

        expect($dto->disk)->toBe('public')
            ->and($dto->path)->toBe('img/logo.png')
            ->and($dto->mime)->toBe('image/png')
            ->and($dto->sizeBytes)->toBe(512);
    });

    it('toArray includes all fields', function () {
        $dto = PreviewDto::fromDisk('s3', 'a.pdf', mime: 'application/pdf', ttl: 600);
        $arr = $dto->toArray();

        expect($arr)->toHaveKeys(['disk', 'path', 'url', 'mime', 'filename', 'sizeBytes', 'view', 'ttl'])
            ->and($arr['ttl'])->toBe(600);
    });
});
