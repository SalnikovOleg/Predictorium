<?php

namespace App\Filament\Resources\LanguageLineResource\Pages;

use App\Filament\Resources\LanguageLineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;
use Illuminate\Support\Arr;

class EditLanguageLine extends EditRecord
{
    protected static string $resource = LanguageLineResource::class;

    protected Width|string|null $maxContentWidth = '6xl';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $text = collect(Arr::pull($data, 'text', []))
            ->mapWithKeys(fn (array $item) => [$item['language'] => $item['value']])
            ->all();

        $record->update(array_merge($data, ['text' => $text]));

        return $record;
    }
}
