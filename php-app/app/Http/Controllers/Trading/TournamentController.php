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

    public function show(Request $request, int $tournamentId): TournamentShowResource|JsonResponse
    {
        $tournament = $this->service->getById($tournamentId);

        if (!$tournament) {
            return $this->notFoundReponse();
        }

        return (new TournamentShowResource($tournament))
            ->additional(['status' => true]);
    }

}
