<?php

namespace App\Services\Trading;

use App\Repositories\Trading\MarketRepository;
use Illuminate\Support\Collection;

class MarketService
{
    public function __construct(
        protected MarketRepository $repository,
    ) {}

    public function getMarketsByEventId(int $eventId): Collection
    {
        return $this->repository->getByEventId($eventId);
    }
}
