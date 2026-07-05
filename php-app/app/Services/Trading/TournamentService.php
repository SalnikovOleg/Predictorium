<?php

namespace App\Services\Trading;

use App\Repositories\Trading\TournamentRepository;
use Illuminate\Support\Collection;

class TournamentService
{
    public function __construct(
        protected TournamentRepository $repository,
    ) {}

    public function getTournamentsByCategoryId(int $categoryId): Collection
    {
        return $this->repository->getByCategoryId($categoryId);
    }
}
