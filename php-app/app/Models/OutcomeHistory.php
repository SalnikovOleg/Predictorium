<?php

namespace App\Models;

use App\Enums\OutcomeResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class OutcomeHistory extends Model
{
    protected $table = 'outcome_history';

    public $timestamps = false;

    protected $fillable = [
        'outcome_id',
        'created_at',
        'result',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'result' => OutcomeResult::class,
        ];
    }

    public function outcome()
    {
        return $this->belongsTo(Outcome::class);
    }

    public static function store(Outcome $outcome, OutcomeResult $previousResult): void
    {
        static::create([
            'outcome_id' => $outcome->id,
            'created_at' => Carbon::now(),
            'result' => $previousResult,
        ]);
    }
}
