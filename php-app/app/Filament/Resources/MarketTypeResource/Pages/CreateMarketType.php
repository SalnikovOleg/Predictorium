<?php

namespace App\Filament\Resources\MarketTypeResource\Pages;

use App\Filament\Resources\MarketTypeResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateMarketType extends CreateRecord
{
    protected static string $resource = MarketTypeResource::class;

    protected Width|string|null $maxContentWidth = '6xl';
}
