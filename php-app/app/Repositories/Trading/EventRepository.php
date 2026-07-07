<?php

namespace App\Repositories\Trading;

use App\Models\Event;
use Illuminate\Support\Collection;

class EventRepository
{
    public function __construct(
        protected Event $model,
    ) {}

    public function getActiveByTournamentId(int $tournamentId): Collection
    {
        return $this->model
            ->with('tournament')
            ->where('status', 'active')
            ->where('tournament_id', $tournamentId)
            ->get();
    }

    public function getById(int $id, string $locale): ?Event
    {
        return $this->model
            ->with([
                'tournament',
                'contents' => fn ($q) => $q->where('lang', $locale),
                'markets.outcomes.outcomeType',
                'markets.outcomes.participant',
            ])
            ->where('id', $id)
            ->first();
    }
}
