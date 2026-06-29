<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupTournament extends Model
{
    protected $fillable = [
        'group_id',
        'tournament_id',
        'name',
        'start_sum',
    ];

    protected function casts(): array
    {
        return [
            'start_sum' => 'decimal:2',
        ];
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
