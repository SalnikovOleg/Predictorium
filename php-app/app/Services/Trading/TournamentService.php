<?php

namespace App\Services\Trading;

use App\Models\Tournament;
use App\Repositories\Trading\TournamentRepository;
use Illuminate\Support\Collection;

class TournamentService
{
    public function __construct(
        protected TournamentRepository $repository,
    ) {}

    public function getTournamentsByCategorySlug(string $slug): Collection
    {
        $locale = app()->getLocale();
        return $this->repository->getByCategorySlug($slug, $locale);
    }

    public function getById(int $tournamentId): ?Tournament
    {
        $locale = app()->getLocale();

        return $this->repository->getById($tournamentId, $locale);
    }
}
