<?php

namespace App\Filament\Resources;

class PartiallyRegisteredResource
{
    public static function getUrl(string $name, array $parameters = []): string
    {
        throw new \RuntimeException('Resource URL generation failed');
    }
}
