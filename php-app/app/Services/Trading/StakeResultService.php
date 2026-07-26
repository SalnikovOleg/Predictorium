<?php

namespace App\Services\Trading;

use App\Enums\OutcomeResult;
use App\Models\Outcome;
use App\Repositories\Trading\StakeRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StakeResultService
{
    public function __construct(
        protected StakeRepository $stakeRepository,
    ) {}

    public function calculateForOutcome(Outcome $outcome): void
    {
        if ($outcome->result === null) {
            return;
        }

        $stakes = $this->stakeRepository->getByMarketAndOutcome(
            $outcome->market_id,
            $outcome->id,
        );

        if ($stakes->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($stakes, $outcome) {
            foreach ($stakes as $stake) {
                $data = [
                    'result' => $outcome->result,
                    'sum_out' => $this->calculateSumOut($stake, $outcome->result),
                ];

                $this->stakeRepository->update($stake, $data);
            }
        });
    }

    protected function calculateSumOut($stake, OutcomeResult $result): float
    {
        return match ($result) {
            OutcomeResult::Win => round((float) $stake->sum_in * (float) $stake->coef, 2),
            OutcomeResult::Lose, OutcomeResult::Return => 0.0,
        };
    }
}
