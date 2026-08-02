<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StakeItem extends Model
{
    protected $fillable = [
        'stake_id',
        'market_id',
        'outcome_id',
        'coef',
        'result'
    ];

    protected function casts(): array
    {
        return [
            'coef' => 'decimal:2',
        ];
    }

    public function stake()
    {
        return $this->belongsTo(Stake::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function outcome()
    {
        return $this->belongsTo(Outcome::class);
    }
}
