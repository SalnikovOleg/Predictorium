<?php

namespace App\Repositories\Web;

use App\Models\SimplePage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SimplePageRepository
{
    public function __construct(
        protected SimplePage $model
    ) {}

    public function findBySlug(string $slug): SimplePage
    {
        $page = $this->model->where('slug', $slug)->with('contents')->first();

        if (!$page) {
            throw new ModelNotFoundException("SimplePage with slug '{$slug}' not found.");
        }

        return $page;
    }

    public function findActiveBySlug(string $slug): SimplePage
    {
        $page = $this->model->where('slug', $slug)->where('is_active', true)->with('contents')->first();

        if (!$page) {
            throw new ModelNotFoundException("Active SimplePage with slug '{$slug}' not found.");
        }

        return $page;
    }
}
