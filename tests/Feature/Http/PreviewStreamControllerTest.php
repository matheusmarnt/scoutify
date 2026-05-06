<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
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

    config([
        'scoutify.authorization.default' => 'permissive',
        'scoutify.preview.allowed_mimes' => ['application/pdf', 'image/jpeg'],
        'scoutify.preview.max_size_bytes' => 10 * 1024 * 1024,
    ]);

    Storage::fake('local');
    Storage::disk('local')->put('reports/q4.pdf', str_repeat('A', 1024));
});

function signedStreamUrl(string $disk, string $path, string $modelClass, string $modelKey, bool $expired = false): string
{
    $expires = $expired ? now()->subMinute() : now()->addMinutes(5);

    return URL::signedRoute('scoutify.preview.stream', [
        'disk' => $disk,
        'path' => $path,
        'model_class' => $modelClass,
        'model_key' => $modelKey,
    ], $expires);
}

function signedDownloadUrl(string $disk, string $path, string $modelClass, string $modelKey): string
{
    return URL::signedRoute('scoutify.preview.download', [
        'disk' => $disk,
        'path' => $path,
        'model_class' => $modelClass,
        'model_key' => $modelKey,
    ], now()->addMinutes(5));
}

it('returns 403 for invalid signature', function () {
    $article = PreviewableArticle::create(['name' => 'Doc', 'file_disk' => 'local', 'file_path' => 'reports/q4.pdf']);
    $user = User::create(['name' => 'User']);
    $this->actingAs($user);

    $this->get('/scoutify/preview/stream?disk=local&path=reports/q4.pdf&model_class=' . urlencode(PreviewableArticle::class) . '&model_key=' . $article->id)
        ->assertStatus(403);
});

it('returns 403 for expired signature', function () {
    $article = PreviewableArticle::create(['name' => 'Doc', 'file_disk' => 'local', 'file_path' => 'reports/q4.pdf']);
    $user = User::create(['name' => 'User']);
    $this->actingAs($user);

    $url = signedStreamUrl('local', 'reports/q4.pdf', PreviewableArticle::class, (string) $article->id, expired: true);
    $this->get($url)->assertStatus(403);
});

it('streams file with valid signature and authenticated user', function () {
    $article = PreviewableArticle::create(['name' => 'Doc', 'file_disk' => 'local', 'file_path' => 'reports/q4.pdf']);
    $user = User::create(['name' => 'User']);
    $this->actingAs($user);

    Storage::disk('local')->put('reports/q4.pdf', '%PDF-1.4 test');
    $url = signedStreamUrl('local', 'reports/q4.pdf', PreviewableArticle::class, (string) $article->id);

    $response = $this->get($url);
    $response->assertStatus(200)
        ->assertHeader('X-Content-Type-Options', 'nosniff');

    expect($response->headers->get('Cache-Control'))->toContain('no-store')
        ->and($response->headers->get('Cache-Control'))->toContain('private');
});

it('downloads file with correct Content-Disposition', function () {
    $article = PreviewableArticle::create(['name' => 'Doc', 'file_disk' => 'local', 'file_path' => 'reports/q4.pdf']);
    $user = User::create(['name' => 'User']);
    $this->actingAs($user);

    Storage::disk('local')->put('reports/q4.pdf', '%PDF-1.4 test');
    $url = signedDownloadUrl('local', 'reports/q4.pdf', PreviewableArticle::class, (string) $article->id);

    $response = $this->get($url);
    $response->assertStatus(200)
        ->assertHeader('X-Content-Type-Options', 'nosniff');

    expect($response->headers->get('Content-Disposition'))->toContain('attachment');
});

it('returns 415 for mime not in whitelist', function () {
    Storage::disk('local')->put('reports/malware.exe', 'MZ');
    $article = PreviewableArticle::create(['name' => 'Exe', 'file_disk' => 'local', 'file_path' => 'reports/malware.exe']);
    $user = User::create(['name' => 'User']);
    $this->actingAs($user);

    $url = signedStreamUrl('local', 'reports/malware.exe', PreviewableArticle::class, (string) $article->id);

    $this->get($url)->assertStatus(415);
});

it('returns 413 when file exceeds max_size_bytes', function () {
    config(['scoutify.preview.max_size_bytes' => 10]);
    Storage::disk('local')->put('reports/big.pdf', str_repeat('A', 100));
    $article = PreviewableArticle::create(['name' => 'Big', 'file_disk' => 'local', 'file_path' => 'reports/big.pdf']);
    $user = User::create(['name' => 'User']);
    $this->actingAs($user);

    $url = signedStreamUrl('local', 'reports/big.pdf', PreviewableArticle::class, (string) $article->id);

    $this->get($url)->assertStatus(413);
});
