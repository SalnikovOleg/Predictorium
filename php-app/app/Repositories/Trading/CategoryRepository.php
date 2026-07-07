<?php

namespace App\Repositories\Trading;

use App\Models\Category;

class CategoryRepository
{
    public function __construct(
        protected Category $model,
    ) {}

    public function getBySlug(string $slug, string $locale): ?Category
    {
        return $this->model
            ->with([
                'contents' => fn ($q) => $q->where('lang', $locale),
                'tournaments' => fn ($q) => $q->where('status', 'active'),
            ])
            ->where('slug', $slug)
            ->first();
    }
}
