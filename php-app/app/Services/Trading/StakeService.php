<?php

namespace App\Services\Trading;

use App\Models\Stake;
use App\Repositories\Trading\StakeRepository;
use Illuminate\Support\Collection;

class StakeService
{
    public function __construct(
        protected StakeRepository $repository,
    ) {}

    public function getByFilters(int $groupId, int $userId, int $eventId): Collection
    {
        return $this->repository->getByFilters($groupId, $userId, $eventId);
    }

    public function store(array $data): Stake
    {
        $existing = $this->repository->findExisting(
            $data['group_id'],
            $data['user_id'],
            $data['event_id'],
            $data['market_id'],
        );

        if ($existing) {
            return $this->repository->update($existing, [
                'outcome_id' => $data['outcome_id'],
                'outcome_ids' => $data['outcome_ids'] ?? null,
            ]);
        }

        return $this->repository->create($data);
    }
}
