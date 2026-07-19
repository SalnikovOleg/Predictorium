<?php

namespace App\Repositories\Trading;

use App\Models\Stake;
use Illuminate\Support\Collection;

class StakeRepository
{
    public function __construct(
        protected Stake $model,
    ) {}

    public function findExisting(
        int $groupId,
        int $userId,
        int $eventId,
        int $marketId,
    ): ?Stake {
        return $this->model
            ->where('group_id', $groupId)
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->where('market_id', $marketId)
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
}
