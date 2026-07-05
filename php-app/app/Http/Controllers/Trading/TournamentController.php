<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trading\TournamentResource;
use App\Services\Trading\TournamentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TournamentController extends Controller
{
    public function __construct(
        protected TournamentService $service,
    ) {}

    public function __invoke(Request $request, int $categoryId): AnonymousResourceCollection|JsonResponse
    {
        $tournaments = $this->service->getTournamentsByCategoryId($categoryId);

        if ($tournaments->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No tournaments found for this category',
            ], 404);
        }

        return TournamentResource::collection($tournaments)
            ->additional(['status' => true]);
    }
}
