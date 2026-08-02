<?php

namespace App\Services\Trading;

use App\Models\Market;
use App\Models\Stake;
use App\Models\StakeItem;
use App\Repositories\Trading\OutcomeRepository;
use App\Repositories\Trading\StakeRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StakeService
{
    public function __construct(
        protected StakeRepository $repository,
        protected OutcomeRepository $outcomeRepository,
    ) {}

    public function getByFilters(int $groupId, int $userId, int $eventId): Collection
    {
        return $this->repository->getByFilters($groupId, $userId, $eventId);
    }

    public function getStatByMarketId(int $marketId): array
    {
        $outcomes = $this->outcomeRepository->getByMarketId($marketId);

        $stakes = $this->repository->getStatByMarketId($marketId);

        $stakesByOutcome = $stakes->pluck('total_stakes', 'outcome_id');

        $totalStakes = (float) $stakes->sum('total_stakes');

        return $outcomes->map(function ($outcome) use ($stakesByOutcome, $totalStakes) {
            $staked = (float) ($stakesByOutcome->get($outcome->id, 0));

            return [
                'outcome_id' => $outcome->id,
                'name' => $outcome->participant->name ?? $outcome->outcomeType->name,
                'percent' => $totalStakes > 0
                    ? round($staked / $totalStakes * 100, 2)
                    : 0.0,
            ];
        })->toArray();
    }

    public function store(array $data): Stake
    {
        $groupId = $data['group_id'];
        $userId = $data['user_id'];
        $eventId = $data['event_id'];
        $marketId = $data['market_id'];
        $outcomeIds = $data['outcome_ids'];

        $market = Market::with('marketTemplate.marketType')->findOrFail($marketId);

        $marketTypeId = $market->marketTemplate?->marketType?->id;
        $param1 = $market->marketTemplate?->param1 ?? 1;

        return DB::transaction(function () use (
            $groupId,
            $userId,
            $eventId,
            $marketId,
            $outcomeIds,
            $marketTypeId,
            $param1
        ) {
            $stake = $this->repository->findExisting($groupId, $userId, $eventId, $marketId);

            if ($stake) {
                $this->updateStakeItems($stake, $outcomeIds, $marketTypeId, $param1);
                return $stake->fresh();
            }

            $stake = $this->repository->create([
                'group_id' => $groupId,
                'user_id' => $userId,
                'event_id' => $eventId,
                'market_id' => $marketId,
                'outcome_id' => $outcomeIds[0] ?? null,
                'sum_in' => 0,
                'coef' => 0,
                'result' => null,
                'sum_out' => 0,
            ]);

            $this->createStakeItems($stake->id, $outcomeIds, $marketId, $marketTypeId, $param1);

            return $stake->fresh();
        });
    }

    private function createStakeItems(int $stakeId, array $outcomeIds, int $marketId, ?int $marketTypeId, int $param1): void
    {
        $items = [];

        if ($marketTypeId === 3) {
            $limitedOutcomeIds = array_slice($outcomeIds, 0, $param1);
        } else {
            $limitedOutcomeIds = $outcomeIds;
        }

        foreach ($limitedOutcomeIds as $outcomeId) {
            $items[] = [
                'stake_id' => $stakeId,
                'market_id' => $marketId,
                'outcome_id' => $outcomeId,
                'coef' => 0,
            ];
        }

        if (!empty($items)) {
            StakeItem::insert($items);
        }
    }

    private function updateStakeItems(Stake $stake, array $outcomeIds, ?int $marketTypeId, int $param1): void
    {
        $existingItems = StakeItem::where('stake_id', $stake->id)->pluck('outcome_id')->toArray();

        if ($marketTypeId === 3) {
            $targetOutcomeIds = array_slice($outcomeIds, 0, $param1);
        } else {
            $targetOutcomeIds = $outcomeIds;
        }

        $toAdd = array_diff($targetOutcomeIds, $existingItems);
        $toRemove = array_diff($existingItems, $targetOutcomeIds);

        if (!empty($toAdd)) {
            $items = [];
            foreach ($toAdd as $outcomeId) {
                $items[] = [
                    'stake_id' => $stake->id,
                    'market_id' => $stake->market_id,
                    'outcome_id' => $outcomeId,
                    'coef' => 0,
                ];
            }
            StakeItem::insert($items);
        }

        if (!empty($toRemove)) {
            StakeItem::where('stake_id', $stake->id)
                ->whereIn('outcome_id', $toRemove)
                ->delete();
        }
    }
}