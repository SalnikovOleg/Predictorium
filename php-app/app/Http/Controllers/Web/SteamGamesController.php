<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Web\SimplePageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SteamGamesController extends Controller
{
    public function __construct(
        protected SimplePageService $pageService
    ) {}

    public function what_to_play(Request $request): JsonResponse
    {
        $page = $this->pageService->getActivePageBySlug('what_to_play');

        return response()->json([
            'status' => true,
            'data' => $page,
        ]);
    }
}
