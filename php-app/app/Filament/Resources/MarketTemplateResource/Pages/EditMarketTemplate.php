<?php

namespace App\Filament\Resources\MarketTemplateResource\Pages;

use App\Filament\Resources\MarketTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditMarketTemplate extends EditRecord
{
    protected static string $resource = MarketTemplateResource::class;

    protected Width|string|null $maxContentWidth = '6xl';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
