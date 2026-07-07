<?php

namespace App\Filament\Resources\LanguageLineResource\Pages;

use App\Filament\Resources\LanguageLineResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;
use Illuminate\Support\Arr;

class CreateLanguageLine extends CreateRecord
{
    protected static string $resource = LanguageLineResource::class;

    protected Width|string|null $maxContentWidth = '6xl';

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $text = collect(Arr::pull($data, 'text', []))
            ->mapWithKeys(fn (array $item) => [$item['language'] => $item['value']])
            ->all();

        return static::getModel()::create(array_merge($data, ['text' => $text]));
    }
}
