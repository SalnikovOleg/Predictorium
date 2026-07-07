<?php

namespace App\Services\Trading;

use App\Models\Category;
use App\Repositories\Trading\CategoryRepository;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $repository,
    ) {}

    public function getBySlug(string $slug): ?Category
    {
        $locale = app()->getLocale();

        return $this->repository->getBySlug($slug, $locale);
    }
}
