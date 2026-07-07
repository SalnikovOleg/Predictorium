<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\ContentPage;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

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
