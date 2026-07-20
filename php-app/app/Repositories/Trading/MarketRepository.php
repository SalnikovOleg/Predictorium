<?php

namespace App\Repositories\Trading;

use App\Models\Market;
use Illuminate\Support\Collection;

class MarketRepository
{
    public function __construct(
        protected Market $model,
    ) {}

    public function getByEventId(int $eventId): Collection
    {
        return $this->model
            ->with(['outcomes.outcomeType', 'outcomes.participant'])
            ->where('event_id', $eventId)
            ->get();
    }

    public function existsByEventAndTemplate(int $eventId, int $templateId): bool
    {
        return $this->model
            ->where('event_id', $eventId)
            ->where('market_template_id', $templateId)
            ->exists();
    }
}
