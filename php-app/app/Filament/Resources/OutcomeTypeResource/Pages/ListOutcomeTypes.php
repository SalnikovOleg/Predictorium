<?php

namespace App\Filament\Resources\OutcomeTypeResource\Pages;

use App\Filament\Resources\OutcomeTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOutcomeTypes extends ListRecords
{
    protected static string $resource = OutcomeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
