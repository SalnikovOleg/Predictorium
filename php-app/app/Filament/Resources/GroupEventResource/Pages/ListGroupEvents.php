<?php

namespace App\Filament\Resources\GroupEventResource\Pages;

use App\Filament\Resources\GroupEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGroupEvents extends ListRecords
{
    protected static string $resource = GroupEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
