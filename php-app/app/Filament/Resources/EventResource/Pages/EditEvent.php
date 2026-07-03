<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Filament\Resources\MarketResource;
use App\Filament\Resources\ResultResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

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
