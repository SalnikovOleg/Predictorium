<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketTemplate extends Model
{
    use SoftDeletes;
    
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
