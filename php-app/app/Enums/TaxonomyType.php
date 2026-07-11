<?php

namespace App\Enums;

enum TaxonomyType: string
{
    case Community = 'community';
    case Country = 'country';

    public function label(): string
    {
        return match ($this) {
            self::Community => 'Community',
            self::Country => 'Country',
        };
    }
}
