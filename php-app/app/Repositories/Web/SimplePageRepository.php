<?php

namespace App\Repositories\Web;

use App\Models\SimplePage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SimplePageRepository
{
    public function __construct(
        protected SimplePage $model
    ) {}

    public function findBySlug(string $slug, string $locale): ?SimplePage
    {
        return $this->model->
            with(['contents' => fn ($q) => $q->where('lang', $locale), 'widgets.widget'])
            ->where('slug', $slug)
            ->first();
    }

    public function findActiveBySlug(string $slug, string $locale): ?SimplePage
    {
        return $this->model->
            with(['contents' => fn ($q) => $q->where('lang', $locale), 'widgets.widget'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
