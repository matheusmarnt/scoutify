<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    config([
        'scout.driver' => 'meilisearch',
        'scout.meilisearch.host' => 'http://localhost:7700',
        'scout.queue' => true,
    ]);

    Http::fake(['http://localhost:7700/health' => Http::response(['status' => 'available'], 200)]);

    Schema::create('failed_jobs', function ($table) {
        $table->id();
        $table->string('uuid')->unique();
        $table->text('connection');
        $table->text('queue');
        $table->longText('payload');
        $table->longText('exception');
        $table->timestamp('failed_at')->useCurrent();
    });
});

afterEach(function () {
    Schema::dropIfExists('failed_jobs');
});

it('prints no failed jobs message when table is empty', function () {
    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('No failed Scout indexing jobs.');
});

it('warns when MakeSearchable jobs are in failed_jobs', function () {
    DB::table('failed_jobs')->insert([
        'uuid' => 'test-uuid',
        'connection' => 'database',
        'queue' => 'default',
        'payload' => '{"displayName":"Laravel\\Scout\\Jobs\\MakeSearchable","job":"..."}',
        'exception' => 'cURL error 6: Could not resolve host: meilisearch',
        'failed_at' => now(),
    ]);

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('1 failed Scout indexing job(s) in failed_jobs table.')
        ->expectsOutputToContain('queue:retry all')
        ->expectsOutputToContain('queue:flush');
});

it('reports correct count for multiple failed MakeSearchable jobs', function () {
    $rows = array_map(fn ($i) => [
        'uuid' => "uuid-{$i}",
        'connection' => 'database',
        'queue' => 'default',
        'payload' => '{"displayName":"MakeSearchable"}',
        'exception' => 'err',
        'failed_at' => now(),
    ], range(1, 3));

    DB::table('failed_jobs')->insert($rows);

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('3 failed Scout indexing job(s)');
});

it('does not warn when failed_jobs has only unrelated jobs', function () {
    DB::table('failed_jobs')->insert([
        'uuid' => 'uuid-other',
        'connection' => 'database',
        'queue' => 'default',
        'payload' => '{"displayName":"App\\Jobs\\SendEmail"}',
        'exception' => 'err',
        'failed_at' => now(),
    ]);

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('No failed Scout indexing jobs.');
});

it('skips failed jobs check when scout.queue is false', function () {
    config(['scout.queue' => false]);

    $this->artisan('scoutify:doctor')
        ->doesntExpectOutputToContain('failed Scout indexing job');
});

it('does not throw when failed_jobs table does not exist', function () {
    Schema::dropIfExists('failed_jobs');

    $this->artisan('scoutify:doctor')->assertSuccessful();
});
