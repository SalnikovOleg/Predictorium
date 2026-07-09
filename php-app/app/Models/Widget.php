<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Widget extends Model
{
    protected $fillable = [
        'name',
        'description',
        'params',
    ];

    protected function casts(): array
    {
        return [
            'params' => 'array',
        ];
    }

    public function widgetsPages(): HasMany
    {
        return $this->hasMany(WidgetsPage::class);
    }
}
