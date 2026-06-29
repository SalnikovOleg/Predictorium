<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function marketTemplates()
    {
        return $this->hasMany(MarketTemplate::class);
    }
}
