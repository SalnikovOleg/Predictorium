<?php

namespace App\Filament\Resources\WidgetsPageResource\Pages;

use App\Filament\Resources\WidgetsPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWidgetsPages extends ListRecords
{
    protected static string $resource = WidgetsPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
