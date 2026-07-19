<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stake extends Model
{
    protected $fillable = [
        'group_id',
        'user_id',
        'event_id',
        'market_id',
        'outcome_id',
        'outcome_ids',
        'sum_in',
        'coef',
        'result',
        'sum_out',
    ];

    protected function casts(): array
    {
        return [
            'outcome_ids' => 'array',
            'sum_in' => 'decimal:2',
            'coef' => 'decimal:2',
            'sum_out' => 'decimal:2',
        ];
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function outcome()
    {
        return $this->belongsTo(Outcome::class);
    }
}
