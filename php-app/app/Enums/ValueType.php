<?php

namespace App\Enums;

enum ValueType: string
{
    case Positions = 'positions';
    case Participant = 'participant';
    case Score = 'score';

    public function label(): string
    {
        return match ($this) {
            self::Positions => 'Positions',
            self::Participant => 'Participant',
            self::Score => 'Score',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Positions => 'info',
            self::Participant => 'success',
            self::Score => 'warning',
        };
    }
}
