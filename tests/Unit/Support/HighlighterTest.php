<?php

use Illuminate\Contracts\Support\Htmlable;
use Matheusmarnt\Scoutify\Support\Highlighter;

it('returns Htmlable instance', function () {
    $h = new Highlighter;
    expect($h->highlight('Hello', 'hello'))->toBeInstanceOf(Htmlable::class);
});

it('wraps case-insensitive match in mark tag', function () {
    $h = new Highlighter;
    $result = (string) $h->highlight('Hello World', 'hello');
    expect($result)->toBe('<mark class="scoutify-mark">Hello</mark> World');
});

it('returns escaped value with no marks when query is empty', function () {
    $h = new Highlighter;
    expect((string) $h->highlight('Hello', ''))->toBe('Hello');
    expect((string) $h->highlight('Hello', '   '))->toBe('Hello');
    expect((string) $h->highlight('Hello', null))->toBe('Hello');
});

it('html-escapes value before marking', function () {
    $h = new Highlighter;
    $result = (string) $h->highlight('<script>alert(1)</script>', 'alert');
    expect($result)->toContain('&lt;script&gt;')
        ->and($result)->toContain('<mark class="scoutify-mark">alert</mark>')
        ->and($result)->not->toContain('<script>');
});

it('treats regex metacharacters in query as literals', function () {
    $h = new Highlighter;
    $result = (string) $h->highlight('1+1=2 and a.b', '+');
    expect($result)->toContain('<mark class="scoutify-mark">+</mark>');

    $result2 = (string) $h->highlight('1+1=2 and a.b', 'a.b');
    expect($result2)->toContain('<mark class="scoutify-mark">a.b</mark>');
});

it('matches unicode characters case-insensitively', function () {
    $h = new Highlighter;
    $result = (string) $h->highlight('Ação de busca', 'ação');
    expect($result)->toContain('<mark class="scoutify-mark">Ação</mark>');
});
