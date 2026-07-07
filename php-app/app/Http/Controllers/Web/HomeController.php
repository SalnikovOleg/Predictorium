<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Web\SimplePageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        protected SimplePageService $pageService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = $this->pageService->getPageBySlug('home');

        return response()->json([
            'status' => true,
            'data' => $page,
        ]);
    }
}
