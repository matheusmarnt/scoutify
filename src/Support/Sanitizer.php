<?php

namespace Matheusmarnt\Scoutify\Support;

final class Sanitizer
{
    public static function toPlainText(?string $value, ?int $limit = null): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value === '') {
            return '';
        }

        // Remove script/style blocks including their text content, not just the tags
        $value = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', ' ', $value);

        // Replace block-level boundaries with space so adjacent words don't merge
        $value = preg_replace('#</?(p|div|br|li|tr|h[1-6]|section|article|header|footer|aside|nav|blockquote|pre)\b[^>]*>#i', ' ', $value);

        $value = strip_tags($value);

        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Collapse whitespace including U+00A0 (non-breaking space from &nbsp; decoding)
        $value = preg_replace('/[\s\x{00A0}]+/u', ' ', $value);

        $value = trim($value);

        if ($limit !== null && mb_strlen($value) > $limit) {
            $value = mb_substr($value, 0, $limit - 3).'...';
        }

        return $value;
    }
}
