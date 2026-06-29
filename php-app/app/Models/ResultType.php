<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultType extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'category_id',
        'name',
        'value_type',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
