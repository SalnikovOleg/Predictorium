<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'tournament_id',
        'name',
        'slug',
        'status',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'status' => EventStatus::class,
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function participants()
    {
        return $this->belongsToMany(Participant::class, 'event_participants')->withTimestamps();
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function markets()
    {
        return $this->hasMany(Market::class);
    }

    public function contents()
    {
        return $this->morphMany(ContentPage::class, 'model');
    }
}
