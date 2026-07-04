<?php

namespace App\Filament\Resources\MarketResource\Pages;

use App\Filament\Resources\MarketResource;
use App\Models\Event;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;

class ListMarkets extends ListRecords
{
    protected static string $resource = MarketResource::class;

    public function getHeading(): string
    {
        $eventId = request()->query('tableFilters')['event_id'] ?? null;

        if ($eventId) {
            $event = Event::find($eventId);
            if ($event) {
                return 'Markets — ' . $event->name;
            }
        }

        return 'Markets';
    }

    public function table(Table $table): Table
    {
        return MarketResource::table($table)
            ->modifyQueryUsing(function ($query) {
                $eventId = request()->query('tableFilters')['event_id'] ?? null;
                if ($eventId) {
                    $query->where('event_id', $eventId);
                }
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->url(fn () => static::getResource()::getUrl('create', [
                    'event_id' => request()->query('tableFilters')['event_id'] ?? null
                ])),
        ];
    }
}
