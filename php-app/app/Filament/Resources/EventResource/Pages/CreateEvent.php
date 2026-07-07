<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

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
