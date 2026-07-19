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

    public function show(Request $request, string $slug): EventShowResource|JsonResponse
    {
        $event = $this->service->getBySlug($slug);

        if (!$event) {
            return $this->notFoundReponse();
        }

        return (new EventShowResource($event))
            ->additional(['status' => true]);
    }
}
