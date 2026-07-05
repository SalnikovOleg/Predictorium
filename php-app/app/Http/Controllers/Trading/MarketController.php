<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarketResource;
use App\Services\Trading\MarketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MarketController extends Controller
{
    public function __construct(
        protected MarketService $service,
    ) {}

    public function __invoke(Request $request, int $eventId): AnonymousResourceCollection|JsonResponse
    {
        $markets = $this->service->getMarketsByEventId($eventId);

        if ($markets->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No markets found for this event',
            ], 404);
        }

        return MarketResource::collection($markets)
            ->additional(['status' => true]);
    }
}
