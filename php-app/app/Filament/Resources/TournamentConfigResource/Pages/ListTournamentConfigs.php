<?php

namespace App\Filament\Resources\TournamentConfigResource\Pages;

use App\Filament\Resources\TournamentConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTournamentConfigs extends ListRecords
{
    protected static string $resource = TournamentConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
