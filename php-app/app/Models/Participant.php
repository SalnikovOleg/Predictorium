<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'taxonomy_id'
    ];

    public function taxonomy()
    {
        return $this->belongsTo(Taxonomy::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_participants')->withTimestamps();
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function outcomes()
    {
        return $this->hasMany(Outcome::class);
    }
}
