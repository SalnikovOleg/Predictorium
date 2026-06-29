<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'event_id',
        'result_type_id',
        'participant_id',
        'time',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'time' => 'integer',
            'value' => 'array',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function resultType()
    {
        return $this->belongsTo(ResultType::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
