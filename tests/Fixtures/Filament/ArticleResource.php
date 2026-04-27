<?php

namespace App\Filament\Resources;

class ArticleResource
{
    public static function getUrl(string $name, array $parameters = []): string
    {
        $key = isset($parameters['record']) ? $parameters['record']->getKey() : 0;

        return "/articles/{$key}";
    }
}
