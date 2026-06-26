<?php

namespace App\Filament\Resources\MarketTypeResource\Pages;

use App\Filament\Resources\MarketTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketTypes extends ListRecords
{
    protected static string $resource = MarketTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
