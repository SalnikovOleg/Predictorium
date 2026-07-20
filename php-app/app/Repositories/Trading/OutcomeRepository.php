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
}
