<?php

namespace App\Filament\Resources\OutcomeTemplateResource\Pages;

use App\Filament\Resources\OutcomeTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOutcomeTemplates extends ListRecords
{
    protected static string $resource = OutcomeTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
