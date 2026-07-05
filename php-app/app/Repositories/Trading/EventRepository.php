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
}
