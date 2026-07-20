<?php

namespace App\Services\Trading;

use App\Models\Stake;
use App\Repositories\Trading\OutcomeRepository;
use App\Repositories\Trading\StakeRepository;
use Illuminate\Support\Collection;

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
                'coef' => ($data['outcome_ids'] ?? null) ? count($data['outcome_ids']) : 1,
                'sum_in' => $data['sum_in'] ?? 1,
            ]);
        }

        $data['coef'] = ($data['outcome_ids'] ?? null) ? count($data['outcome_ids']) : 1;
        $data['sum_in'] = $data['sum_in'] ?? 1;

        return $this->repository->create($data);
    }
}
