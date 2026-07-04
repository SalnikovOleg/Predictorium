<?php

namespace App\Models;

use App\Enums\OutcomeResult;
use Illuminate\Database\Eloquent\Model;

class Outcome extends Model
{
    protected $fillable = [
        'market_id',
        'outcome_type_id',
        'participant_id',
        'coef',
        'result',
    ];

    protected function casts(): array
    {
        return [
            'result' => OutcomeResult::class,
            'coef' => 'decimal:2',
        ];
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function outcomeType()
    {
        return $this->belongsTo(OutcomeType::class, 'outcome_type_id');
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
