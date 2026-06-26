<?php

namespace App\Filament\Resources\MarketTemplateResource\Pages;

use App\Filament\Resources\MarketTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketTemplates extends ListRecords
{
    protected static string $resource = MarketTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
