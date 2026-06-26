<?php

namespace App\Filament\Resources\TournamentConfigResource\Pages;

use App\Filament\Resources\TournamentConfigResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateTournamentConfig extends CreateRecord
{
    protected static string $resource = TournamentConfigResource::class;

    protected Width|string|null $maxContentWidth = '6xl';
}
