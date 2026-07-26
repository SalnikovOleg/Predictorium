<?php

namespace App\Enums;

enum TaxonomyType: string
{
    case Community = 'community';
    case Country = 'country';
    case General = 'general';
    public function label(): string
    {
        return match ($this) {
            self::Community => 'Community',
            self::Country => 'Country',
            self::General => 'General',
        };
    }
}
