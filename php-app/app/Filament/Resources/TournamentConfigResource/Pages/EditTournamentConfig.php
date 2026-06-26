<?php

namespace App\Filament\Resources\TournamentConfigResource\Pages;

use App\Filament\Resources\TournamentConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditTournamentConfig extends EditRecord
{
    protected static string $resource = TournamentConfigResource::class;

    protected Width|string|null $maxContentWidth = '6xl';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
