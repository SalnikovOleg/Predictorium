<?php

namespace App\Services\Web;

use App\Models\SimplePage;
use App\Repositories\Web\SimplePageRepository;

class SimplePageService
{
    public function __construct(
        protected SimplePageRepository $repository
    ) {}

    public function getPageBySlug(string $slug): SimplePage
    {
        return $this->repository->findBySlug($slug);
    }

    public function getActivePageBySlug(string $slug): SimplePage
    {
        return $this->repository->findActiveBySlug($slug);
    }
}
