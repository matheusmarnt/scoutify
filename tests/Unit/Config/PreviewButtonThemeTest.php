<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Matheusmarnt\Scoutify\ScoutifyManager;

$minimalResult = [
    'preview' => ['mime' => 'application/pdf', 'path' => 'file.pdf', 'disk' => 'local', 'label' => 'File', 'downloadable' => true],
    'modelKey' => 'App\\Models\\Stub::1',
];

it('getPreviewButton returns null by default', function () {
    expect(app(ScoutifyManager::class)->theme()->getPreviewButton())->toBeNull();
});

it('getDownloadButton returns null by default', function () {
    expect(app(ScoutifyManager::class)->theme()->getDownloadButton())->toBeNull();
});

it('getPreviewBackButton returns null by default', function () {
    expect(app(ScoutifyManager::class)->theme()->getPreviewBackButton())->toBeNull();
});

it('previewButton setter stores value and getter returns it', function () {
    app(ScoutifyManager::class)->theme()->previewButton('custom-preview-btn');

    expect(app(ScoutifyManager::class)->theme()->getPreviewButton())->toBe('custom-preview-btn');
});

it('downloadButton setter stores value and getter returns it', function () {
    app(ScoutifyManager::class)->theme()->downloadButton('custom-download-btn');

    expect(app(ScoutifyManager::class)->theme()->getDownloadButton())->toBe('custom-download-btn');
});

it('previewBackButton setter stores value and getter returns it', function () {
    app(ScoutifyManager::class)->theme()->previewBackButton('custom-back-btn');

    expect(app(ScoutifyManager::class)->theme()->getPreviewBackButton())->toBe('custom-back-btn');
});

it('custom previewButton class appears in result-row-actions HTML', function () use ($minimalResult) {
    Artisan::call('view:clear');

    app(ScoutifyManager::class)->theme()->previewButton('my-preview-btn-xyz');

    $html = Blade::render(
        '<x-scoutify::gs.result-row-actions :result="$result" />',
        ['result' => $minimalResult]
    );

    expect($html)->toContain('my-preview-btn-xyz');
});

it('custom downloadButton class appears in result-row-actions HTML', function () use ($minimalResult) {
    Artisan::call('view:clear');

    app(ScoutifyManager::class)->theme()->downloadButton('my-download-btn-xyz');

    $html = Blade::render(
        '<x-scoutify::gs.result-row-actions :result="$result" />',
        ['result' => $minimalResult]
    );

    expect($html)->toContain('my-download-btn-xyz');
});

it('custom previewBackButton class appears in preview header HTML', function () {
    Artisan::call('view:clear');

    app(ScoutifyManager::class)->theme()->previewBackButton('my-back-btn-xyz');

    $html = Blade::render('<x-scoutify::gs.preview.header />');

    expect($html)->toContain('my-back-btn-xyz');
});

it('null previewButton renders without extra class (no double space or trailing garbage)', function () use ($minimalResult) {
    Artisan::call('view:clear');

    $html = Blade::render(
        '<x-scoutify::gs.result-row-actions :result="$result" />',
        ['result' => $minimalResult]
    );

    expect($html)->toContain('focus-visible:ring-2');
});
