<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TournamentConfig extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'rules_json',
        'market_template_ids',
    ];

    protected function casts(): array
    {
        return [
            'rules_json' => 'array',
            'market_template_ids' => 'array',
        ];
    }

    public function tournaments()
    {
        return $this->hasMany(Tournament::class, 'config_id');
    }
}
