<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupEvent extends Model
{
    protected $fillable = [
        'group_id',
        'tournament_id',
        'event_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
