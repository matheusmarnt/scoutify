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

        $pattern = '/('.preg_quote($query, '/').')/iu';
        $marked = preg_replace_callback(
            $pattern,
            fn (array $m) => '<mark class="scoutify-mark">'.$m[1].'</mark>',
            $escaped,
        );

        return new HtmlString($marked ?? $escaped);
    }
}
