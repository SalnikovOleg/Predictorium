<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $fillable = [
        'event_id',
        'market_template_id',
        'description',
        'sort_order',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function marketTemplate()
    {
        return $this->belongsTo(MarketTemplate::class);
    }

    public function outcomes()
    {
        return $this->hasMany(Outcome::class);
    }
}
