<?php

namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateTournament extends CreateRecord
{
    protected static string $resource = TournamentResource::class;

    protected Width|string|null $maxContentWidth = '6xl';
}
