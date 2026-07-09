<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WidgetsPage extends Model
{
    protected $fillable = [
        'widget_id',
        'model_type',
        'model_id',
        'params',
    ];

    protected function casts(): array
    {
        return [
            'params' => 'array',
        ];
    }

    public function widget(): BelongsTo
    {
        return $this->belongsTo(Widget::class);
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
