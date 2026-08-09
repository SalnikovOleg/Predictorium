<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trading\StoreStakeRequest;
use App\Http\Resources\Trading\StakeResource;
use App\Services\Trading\StakeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StakeController extends Controller
{
    public function __construct(
        protected StakeService $service,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $stakes = $this->service->getByFilters(
            $request->integer('group_id'),
            $request->integer('user_id'),
            $request->integer('event_id'),
        );

        $data = $stakes->map(function ($stake) {
            return [
                'id' => $stake->id,
                'stake_items' => $stake->stakeItems->map(fn ($item) => [
                    'market_id' => $item->market_id,
                    'outcome_id' => $item->outcome_id,
                    'result' => $item->result,
                ]),
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function stat(int $marketId): JsonResponse
    {
        $stats = $this->service->getStatByMarketId($marketId);

        return response()->json([
            'status' => true,
            'data' => $stats,
        ]);
    }

    public function store(StoreStakeRequest $request): StakeResource|JsonResponse
    {
        $stake = $this->service->store($request->validated());

        return (new StakeResource($stake))
            ->additional([
                'status' => true,
                'message' => 'Stake saved successfully',
            ]);
    }
}
