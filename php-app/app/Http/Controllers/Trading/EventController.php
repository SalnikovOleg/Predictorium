<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trading\EventShowResource;
use App\Services\Trading\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(
        protected EventService $service,
    ) {}

    public function show(Request $request, int $tournamentId): EventShowResource|JsonResponse
    {
        $event = $this->service->getById($tournamentId);

        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found',
            ], 404);
        }

        return (new EventShowResource($event))
            ->additional(['status' => true]);
    }
}
