<?php

namespace App\Filament\Resources\ResultTypeResource\Pages;

use App\Filament\Resources\ResultTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResultTypes extends ListRecords
{
    protected static string $resource = ResultTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
