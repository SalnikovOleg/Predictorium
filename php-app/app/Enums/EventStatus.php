<?php

namespace App\Enums;

enum EventStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Progress = 'progress';
    case Finished = 'finished';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Active => 'Active',
            self::Progress => 'progress',
            self::Finished => 'Finished',
            self::Archived => 'Archived',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Active => 'success',
            self::Progress => 'blue',
            self::Finished => 'info',
            self::Archived => 'danger',
        };
    }
}
