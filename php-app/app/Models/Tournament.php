<?php

namespace App\Models;

use App\Enums\TournamentStatus;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'config_id',
        'status',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'status' => TournamentStatus::class,
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function config()
    {
        return $this->belongsTo(TournamentConfig::class, 'config_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
