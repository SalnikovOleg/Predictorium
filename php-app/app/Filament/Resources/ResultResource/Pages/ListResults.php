<?php

namespace App\Filament\Resources\ResultResource\Pages;

use App\Filament\Resources\ResultResource;
use App\Models\Event;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResults extends ListRecords
{
    protected static string $resource = ResultResource::class;

    public function getHeading(): string
    {
        $eventId = request()->query('tableFilters')['event_id'] ?? null;

        if ($eventId) {
            $event = Event::find($eventId);
            if ($event) {
                return 'Results — ' . $event->name;
            }
        }

        return 'Results';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->form(fn () => ResultResource::getModalForm()),
        ];
    }
}
