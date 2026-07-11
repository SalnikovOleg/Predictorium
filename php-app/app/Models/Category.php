<?php

namespace App\Models;

use App\Enums\TaxonomyType;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'taxonomy_type',
        'is_active',
        'sort_order',
        'icon',
    ];

    protected function casts(): array
    {
        return [
            'taxonomy_type' => TaxonomyType::class,
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function tournaments()
    {
        return $this->hasMany(Tournament::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function resultTypes()
    {
        return $this->hasMany(ResultType::class);
    }

    public function contents()
    {
        return $this->morphMany(ContentPage::class, 'model');
    }
}
