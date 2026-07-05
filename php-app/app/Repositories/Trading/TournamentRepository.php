<?php

namespace App\Repositories\Trading;

use App\Models\Tournament;
use Illuminate\Support\Collection;

class TournamentRepository
{
    public function __construct(
        protected Tournament $model,
    ) {}

    public function getByCategorySlug(string $slug): Collection
    {
        return $this->model
            ->with('category')
            ->where('status', 'active')
            ->whereHas('category', fn ($q) => $q->where('slug', $slug))
            ->get();
    }

    public function getByCategoryId(int $categoryId): Collection
    {
        return $this->model
            ->with('category')
            ->where('status', 'active')
            ->where('category_id', $categoryId)
            ->get();
    }
}
