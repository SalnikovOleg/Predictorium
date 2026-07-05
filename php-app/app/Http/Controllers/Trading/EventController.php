<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trading\EventResource;
use App\Services\Trading\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{
    public function __construct(
        protected EventService $service,
    ) {}

    public function __invoke(Request $request, int $tournamentId): AnonymousResourceCollection|JsonResponse
    {
        $events = $this->service->getActiveEventsByTournamentId($tournamentId);

        if ($events->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No active events found for this tournament',
            ], 404);
        }

        return EventResource::collection($events)
            ->additional(['status' => true]);
    }
}
