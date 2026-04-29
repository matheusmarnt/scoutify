<?php

namespace Matheusmarnt\Scoutify\Support;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Highlighter
{
    public function highlight(?string $value, ?string $query): Htmlable
    {
        $escaped = e((string) $value);

        if ($query === null || trim($query) === '') {
            return new HtmlString($escaped);
        }

        // NFD decomposes accented chars into base + combining marks (e.g. "ã" → "ã")
        $nfdValue = \Normalizer::normalize($escaped, \Normalizer::NFD);
        $nfdQuery = \Normalizer::normalize($query, \Normalizer::NFD);

        // Strip combining marks from query so "padrao" and "padrão" build the same pattern
        $strippedQuery = preg_replace('/\p{Mn}/u', '', $nfdQuery) ?? $nfdQuery;

        if (trim($strippedQuery) === '') {
            return new HtmlString($escaped);
        }

        // Each base char matches itself + any attached combining marks in the text
        $parts = array_map(fn (string $c) => preg_quote($c, '/').'\p{Mn}*', mb_str_split($strippedQuery));
        $pattern = '/('.implode('', $parts).')/iu';

        $marked = preg_replace_callback(
            $pattern,
            fn (array $m) => '<mark class="scoutify-mark">'.$m[1].'</mark>',
            $nfdValue,
        );

        // Normalize back to NFC — preg_replace ran on NFD-normalized string, output is still NFD
        $result = \Normalizer::normalize($marked ?? $escaped, \Normalizer::NFC);

        return new HtmlString($result !== false ? $result : ($marked ?? $escaped));
    }
}
