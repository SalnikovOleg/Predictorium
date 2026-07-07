<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimplePage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'is_active',
        'params_json',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'params_json' => 'array',
        ];
    }

    public function contents(): MorphMany
    {
        return $this->morphMany(ContentPage::class, 'model');
    }
}
