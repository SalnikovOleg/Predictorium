<?php

namespace App\Filament\Resources\MarketTemplateResource\Pages;

use App\Filament\Resources\MarketTemplateResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateMarketTemplate extends CreateRecord
{
    protected static string $resource = MarketTemplateResource::class;

    protected Width|string|null $maxContentWidth = '6xl';
}
