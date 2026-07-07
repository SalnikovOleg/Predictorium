<?php

namespace App\Services\Trading;

use App\Models\Event;
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

    public function getById(int $eventId): ?Event
    {
        $locale = app()->getLocale();

        return $this->repository->getById($eventId, $locale);
    }
}
