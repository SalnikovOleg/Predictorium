<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Web\NavigationResource;
use App\Services\Web\NavigationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NavController extends Controller
{
    public function __construct(
        protected NavigationService $service,
    ) {}

    public function __invoke(Request $request): JsonResponse|JsonResource
    {
        $navigation = $this->service->getMainMenu();

        if (!$navigation) {
            return response()->json([
                'status' => false,
                'message' => 'Main menu not found',
            ], 404);
        }

        return (new NavigationResource($navigation))->additional(['status' => true]);
    }
}
