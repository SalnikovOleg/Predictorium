<?php

namespace App\Enums;

enum TournamentStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Finished = 'finished';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Active => 'Active',
            self::Finished => 'Finished',
            self::Archived => 'Archived',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Active => 'success',
            self::Finished => 'info',
            self::Archived => 'danger',
        };
    }
}
