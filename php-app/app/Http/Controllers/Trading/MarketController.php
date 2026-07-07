<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Services\Trading\MarketService;

class MarketController extends Controller
{
    public function __construct(
        protected MarketService $service,
    ) {}

}
