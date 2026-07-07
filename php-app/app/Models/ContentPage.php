<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContentPage extends Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'lang',
        'title',
        'content',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
