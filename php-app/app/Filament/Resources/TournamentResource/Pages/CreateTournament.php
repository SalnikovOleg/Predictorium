<?php

namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateTournament extends CreateRecord
{
    protected static string $resource = TournamentResource::class;

    protected Width|string|null $maxContentWidth = '6xl';

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $pagesData = $data['pages'] ?? [];
        unset($data['pages']);

        $record = parent::handleRecordCreation($data);

        foreach ($pagesData as $pageData) {
            $record->contents()->create($pageData);
        }

        return $record;
    }
}
