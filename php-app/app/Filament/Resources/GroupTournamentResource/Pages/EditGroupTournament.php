<?php

namespace App\Filament\Resources\GroupTournamentResource\Pages;

use App\Filament\Resources\GroupTournamentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGroupTournament extends EditRecord
{
    protected static string $resource = GroupTournamentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
