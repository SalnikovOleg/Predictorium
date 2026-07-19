<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trading\TournamentShowResource;
use App\Services\Trading\TournamentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function __construct(
        protected TournamentService $service,
    ) {}

    public function show(Request $request, string $slug): TournamentShowResource|JsonResponse
    {
        $tournament = $this->service->getBySlug($slug);

        if (!$tournament) {
            return $this->notFoundReponse();
        }

        return (new TournamentShowResource($tournament))
            ->additional(['status' => true]);
    }

}
