<?php

namespace App\Models;

use App\Enums\TaxonomyType;
use App\Enums\TournamentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'ext_id',
        'icon',
        'params',
    ];

    protected function casts(): array
    {
        return [
            'params' => 'array',
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

    public function contents()
    {
        return $this->morphMany(ContentPage::class, 'model');
    }

    public function getIconUrlAttribute()
    {
        return $this->icon ? Storage::disk(config('filesystems.default'))->url($this->icon) : null;
    }
}
