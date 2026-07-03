<?php

namespace App\Filament\Resources\MarketTypeResource\Pages;

use App\Filament\Resources\MarketTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditMarketType extends EditRecord
{
    protected static string $resource = MarketTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
