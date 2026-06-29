<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'group_id',
        'tournament_id',
        'sum',
        'points',
    ];

    protected function casts(): array
    {
        return [
            'sum' => 'decimal:2',
            'points' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
