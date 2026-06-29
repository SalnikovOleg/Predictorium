<?php

namespace App\Filament\Resources\ResultTypeResource\Pages;

use App\Filament\Resources\ResultTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditResultType extends EditRecord
{
    protected static string $resource = ResultTypeResource::class;

    protected Width|string|null $maxContentWidth = '6xl';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
