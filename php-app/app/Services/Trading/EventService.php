<?php

namespace App\Services\Trading;

use App\Repositories\Trading\EventRepository;
use Illuminate\Support\Collection;

class EventService
{
    public function __construct(
        protected EventRepository $repository,
    ) {}

    public function getActiveEventsByTournamentId(int $tournamentId): Collection
    {
        return $this->repository->getActiveByTournamentId($tournamentId);
    }
}
