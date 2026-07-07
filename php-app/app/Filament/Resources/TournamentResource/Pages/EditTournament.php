<?php

namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
use App\Models\ContentPage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditTournament extends EditRecord
{
    protected static string $resource = TournamentResource::class;

    protected Width|string|null $maxContentWidth = '6xl';

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $pagesData = $data['pages'] ?? [];
        unset($data['pages']);

        $existingPages = $this->record->contents()->get()->keyBy('lang');

        foreach ($pagesData as $pageData) {
            $lang = $pageData['lang'];
            if ($existingPages->has($lang)) {
                $existingPages[$lang]->update($pageData);
            } else {
                $this->record->contents()->create($pageData);
            }
        }

        $submittedLangs = collect($pagesData)->pluck('lang')->toArray();
        $existingPages->filter(fn (ContentPage $page) => !in_array($page->lang, $submittedLangs))
            ->each->delete();

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
