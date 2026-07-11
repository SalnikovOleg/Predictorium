<?php

namespace App\Models;

use App\Enums\TaxonomyType;
use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
    protected $fillable = [
        'type',
        'name',
        'icon'
    ];

    protected function casts(): array
    {
        return [
            'type' => TaxonomyType::class,
        ];
    }
}
