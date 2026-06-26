<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketTemplate extends Model
{
    protected $fillable = [
        'name',
        'market_type_id',
        'outcome_template_ids',
    ];

    protected function casts(): array
    {
        return [
            'outcome_template_ids' => 'array',
        ];
    }

    public function marketType()
    {
        return $this->belongsTo(MarketType::class);
    }
}
