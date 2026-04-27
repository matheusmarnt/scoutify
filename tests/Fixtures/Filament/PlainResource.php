<?php

namespace App\Filament\Resources;

class PlainResource
{
    public static function getUrl(string $name, array $parameters = []): string
    {
        $key = isset($parameters['record']) ? $parameters['record']->getKey() : 0;

        return "/plains/{$key}";
    }
}
