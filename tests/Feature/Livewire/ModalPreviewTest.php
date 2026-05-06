<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\ExternalPreviewArticle;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\PreviewableArticle;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\User;

beforeEach(function () {
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->timestamps();
    });

    Schema::create('previewable_articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('file_disk')->nullable();
        $table->string('file_path')->nullable();
        $table->timestamps();
    });

    config(['scoutify.authorization.default' => 'permissive']);

    Storage::fake('local');
    Storage::disk('local')->put('reports/q4.pdf', '%PDF-1.4');

    $user = User::create(['name' => 'User']);
    $this->actingAs($user);
});

it('openPreview populates selectedPreview for storage-based file', function () {
    $article = PreviewableArticle::create([
        'name' => 'PDF Report',
        'file_disk' => 'local',
        'file_path' => 'reports/q4.pdf',
    ]);

    $component = Livewire::test(Modal::class)
        ->call('open')
        ->set('query', 'PDF Report')
        ->call('search');

    $results = $component->get('results');
    $modelKey = collect($results)->firstWhere('modelKey', (string) $article->id)['modelKey'] ?? null;

    expect($modelKey)->not->toBeNull();

    $component->call('openPreview', $modelKey);

    $selectedPreview = $component->get('selectedPreview');

    expect($selectedPreview)->not->toBeNull()
        ->and($selectedPreview['filename'])->toBe('q4.pdf')
        ->and($selectedPreview['resolved_mime'])->toBe('application/pdf')
        ->and($selectedPreview['stream_url'])->not->toBeNull();
});

it('openPreview populates selectedPreview for external url', function () {
    ExternalPreviewArticle::create(['name' => 'External Report']);

    $component = Livewire::test(Modal::class)
        ->call('open')
        ->set('query', 'External Report')
        ->call('search');

    $results = $component->get('results');
    $modelKey = collect($results)->first()['modelKey'] ?? null;

    $component->call('openPreview', $modelKey);

    $selectedPreview = $component->get('selectedPreview');

    expect($selectedPreview)->not->toBeNull()
        ->and($selectedPreview['stream_url'])->toBe('https://cdn.example.com/report.pdf')
        ->and($selectedPreview['download_url'])->toBe('https://cdn.example.com/report.pdf');
});

it('closePreview resets selectedPreview to null', function () {
    $article = PreviewableArticle::create([
        'name' => 'Close Test',
        'file_disk' => 'local',
        'file_path' => 'reports/q4.pdf',
    ]);

    $component = Livewire::test(Modal::class)
        ->call('open')
        ->set('query', 'Close Test')
        ->call('search');

    $results = $component->get('results');
    $modelKey = collect($results)->first()['modelKey'] ?? null;

    $component->call('openPreview', $modelKey)
        ->call('closePreview');

    expect($component->get('selectedPreview'))->toBeNull();
});

it('close resets selectedPreview', function () {
    $component = Livewire::test(Modal::class)
        ->set('selectedPreview', ['filename' => 'test.pdf']);

    $component->call('close');

    expect($component->get('selectedPreview'))->toBeNull();
});

it('does nothing when openPreview called with unknown modelKey', function () {
    $component = Livewire::test(Modal::class)
        ->call('open')
        ->set('query', 'nothing')
        ->call('search');

    $component->call('openPreview', 'nonexistent-key-999');

    expect($component->get('selectedPreview'))->toBeNull();
});
