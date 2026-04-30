<?php

use Matheusmarnt\Scoutify\Support\Sanitizer;

it('returns null for null input', function () {
    expect(Sanitizer::toPlainText(null))->toBeNull();
});

it('returns empty string for empty input', function () {
    expect(Sanitizer::toPlainText(''))->toBe('');
});

it('is idempotent for plain text', function () {
    expect(Sanitizer::toPlainText('Hello world'))->toBe('Hello world');
});

it('strips html tags and returns plain text', function () {
    expect(Sanitizer::toPlainText('<p>Hello <strong>world</strong></p>'))->toBe('Hello world');
});

it('removes script block content not just the tags', function () {
    expect(Sanitizer::toPlainText('<script>alert(1)</script>foo'))->toBe('foo');
});

it('removes style block content not just the tags', function () {
    expect(Sanitizer::toPlainText('<style>.x { color: red }</style>bar'))->toBe('bar');
});

it('decodes html entities including nbsp', function () {
    expect(Sanitizer::toPlainText('a &amp; b &lt; c &nbsp;d'))->toBe('a & b < c d');
});

it('collapses whitespace from block boundaries', function () {
    expect(Sanitizer::toPlainText("<p>a</p>\n\n<p>b</p>"))->toBe('a b');
});

it('does not truncate when content fits within limit', function () {
    expect(Sanitizer::toPlainText('<p>Short text</p>', 150))->toBe('Short text');
});

it('truncates to limit without leaving broken tags in output', function () {
    $input = '<p>'.str_repeat('a', 200).'</p>';
    $result = Sanitizer::toPlainText($input, 150);

    expect(mb_strlen($result))->toBeLessThanOrEqual(150)
        ->and($result)->toEndWith('...')
        ->and($result)->not->toContain('<')
        ->and($result)->not->toContain('>');
});

it('handles multibyte characters safely when truncating', function () {
    $input = '<p>'.str_repeat('é', 200).'</p>';
    $result = Sanitizer::toPlainText($input, 150);

    expect(mb_strlen($result))->toBeLessThanOrEqual(150)
        ->and($result)->toEndWith('...');
});
