<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outcome extends Model
{
    protected $fillable = [
        'market_id',
        'outcome_type_id',
        'participant_id',
        'coef',
    ];

    protected function casts(): array
    {
        return [
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
