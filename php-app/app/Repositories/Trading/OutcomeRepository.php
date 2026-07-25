<?php

namespace App\Repositories\Trading;

use App\Models\Outcome;
use Illuminate\Database\Eloquent\Collection;

class OutcomeRepository
{
    public function __construct(
        protected Outcome $model,
    ) {}

    public function getByMarketId(int $marketId): Collection
    {
        return $this->model->where('market_id', $marketId)->get();
    }

    public function getWithMarketTemplateForEvent(int $eventId): Collection
    {
        return $this->model
            ->whereHas('market', fn ($q) => $q->where('event_id', $eventId))
            ->with('market.marketTemplate')
            ->get();
    }

    public function bulkUpdateResult(array $updates): void
    {
        foreach ($updates as $id => $result) {
            $this->model->where('id', $id)->update(['result' => $result]);
        }
    }
}
