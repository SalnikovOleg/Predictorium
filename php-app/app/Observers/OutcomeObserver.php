<?php

namespace App\Observers;

use App\Enums\OutcomeResult;
use App\Models\Outcome;
use App\Models\OutcomeHistory;

class OutcomeObserver
{
    private static array $previousResults = [];

    public function updating(Outcome $outcome): void
    {
        $dirty = $outcome->getDirty();

        if (!isset($dirty['result']) || is_null($dirty['result'])) {
            return;
        }

        $previousResult = $outcome->getOriginal('result');

        if (is_null($previousResult)) {
            return;
        }

        self::$previousResults[$outcome->id] = $previousResult instanceof OutcomeResult
            ? $previousResult
            : OutcomeResult::tryFrom($previousResult);
    }

    public function updated(Outcome $outcome): void
    {
        if (!isset(self::$previousResults[$outcome->id])) {
            return;
        }

        /** @var OutcomeResult $previousResult */
        $previousResult = self::$previousResults[$outcome->id];

        OutcomeHistory::store($outcome, $previousResult);

        unset(self::$previousResults[$outcome->id]);
    }
}
