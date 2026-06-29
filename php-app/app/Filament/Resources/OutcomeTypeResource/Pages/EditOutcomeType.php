<?php

namespace App\Filament\Resources\OutcomeTypeResource\Pages;

use App\Filament\Resources\OutcomeTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditOutcomeType extends EditRecord
{
    protected static string $resource = OutcomeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
