<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Filament\Resources\MarketResource;
use App\Filament\Resources\ResultResource;
use App\Models\ContentPage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

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
            Actions\Action::make('markets')
                ->label('Markets')
                ->icon('heroicon-o-banknotes')
                ->url(fn () => MarketResource::getUrl('index', ['tableFilters' => ['event_id' => $this->record->id]])),
            Actions\Action::make('results')
                ->label('Results')
                ->icon('heroicon-o-document-text')
                ->url(fn () => ResultResource::getUrl('index', ['tableFilters' => ['event_id' => $this->record->id]])),
        ];
    }
}
