<?php

namespace App\Repositories\Trading;

use App\Models\Stake;
use Illuminate\Support\Collection;

class StakeRepository
{
    public function __construct(
        protected Stake $model,
    ) {}

    public function findExistingByStakeItemMarket(
        int $groupId,
        int $userId,
        int $eventId,
        int $marketId,
    ): ?Stake {
        return $this->model
            ->where('group_id', $groupId)
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->whereHas('stakeItems', function ($query) use ($marketId) {
                $query->where('market_id', $marketId);
            })
            ->first();
    }

    public function getByFilters(
        int $groupId,
        int $userId,
        int $eventId,
    ): Collection {
        return $this->model
            ->where('group_id', $groupId)
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->with('stakeItems')
            ->get();
    }

    public function create(array $data): Stake
    {
        return $this->model->create($data);
    }

    public function update(Stake $stake, array $data): Stake
    {
        $stake->update($data);

        return $stake->fresh();
    }

    public function getStatByMarketId(int $marketId): Collection
    {
        return $this->model
            ->join('stake_items', 'stakes.id', '=', 'stake_items.stake_id')
            ->where('stake_items.market_id', $marketId)
            ->selectRaw('stake_items.outcome_id, SUM(stakes.sum_in) as total_stakes')
            ->groupBy('stake_items.outcome_id')
            ->get();
    }

    public function getByMarketAndOutcome(int $marketId, int $outcomeId): Collection
    {
        return $this->model
            ->where('market_id', $marketId)
            ->where('outcome_id', $outcomeId)
            ->get();
    }
}
