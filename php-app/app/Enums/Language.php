<?php

namespace App\Enums;

enum Language: string
{
    case En = 'en';
    case Uk = 'uk';

    public function label(): string
    {
        return match ($this) {
            self::En => 'English',
            self::Uk => 'Ukrainian',
        };
    }
}
