<?php

namespace App\Enums;

enum OutcomeResult: string
{
    case Win = 'win';
    case Lose = 'lose';
    case Return = 'return';

    public function label(): string
    {
        return match ($this) {
            self::Win => 'Win',
            self::Lose => 'Lose',
            self::Return => 'Return',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Win => 'success',
            self::Lose => 'danger',
            self::Return => 'warning',
        };
    }
}
