<?php

namespace App\Filament\Resources\OutcomeTemplateResource\Pages;

use App\Filament\Resources\OutcomeTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditOutcomeTemplate extends EditRecord
{
    protected static string $resource = OutcomeTemplateResource::class;

    protected Width|string|null $maxContentWidth = '6xl';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
