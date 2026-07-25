<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'market_type_id',
        'result_type_id',
        'outcome_type_ids',
    ];

    protected function casts(): array
    {
        return [
            'outcome_type_ids' => 'array',
        ];
    }

    public function marketType()
    {
        return $this->belongsTo(MarketType::class);
    }

    public function resultType()
    {
        return $this->belongsTo(ResultType::class);
    }
}
