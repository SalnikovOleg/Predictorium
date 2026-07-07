<?php

namespace App\Repositories\Trading;

use App\Models\Tournament;
use Illuminate\Support\Collection;

class TournamentRepository
{
    public function __construct(
        protected Tournament $model,
    ) {}

    public function getByCategorySlug(string $slug, string $locale): Collection
    {
        return $this->model
            ->with('category')
            ->where('status', 'active')
            ->whereHas('category', fn ($q) => $q->where('slug', $slug))
            ->get();
    }

    public function getByCategoryId(int $categoryId, string $locale): Collection
    {
        return $this->model
            ->with(['category' => function ($query) use ($locale) {
                $query->with(['contents' => function ($subQuery) use ($locale) {
                    $subQuery->where('lang', $locale);
                }]);
            }])
            ->where('status', 'active')
            ->where('category_id', $categoryId)
            ->get();
    }

    public function getBySlug(string $slug, string $locale): ?Tournament
    {
        return $this->model
            ->with([
                'category',
                'contents' => fn ($q) => $q->where('lang', $locale),
                'events' => fn ($q) => $q->where('status', 'active'),
            ])
            ->where('slug', $slug)
            ->first();
    }

    public function getById(int $id, string $locale): ?Tournament
    {
        return $this->model
            ->with([
                'category',
                'contents' => fn ($q) => $q->where('lang', $locale),
                'events' => fn ($q) => $q->where('status', 'active'),
            ])
            ->where('id', $id)
            ->first();
    }
}
