<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trading\CategoryResource;
use App\Services\Trading\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $service,
    ) {}

    public function show(Request $request, string $slug): JsonResponse|JsonResource
    {
        $category = $this->service->getBySlug($slug);

        if (!$category) {
            return $this->notFoundReponse();
        }

        return (new CategoryResource($category))
            ->additional(['status' => true]);
    }
}
