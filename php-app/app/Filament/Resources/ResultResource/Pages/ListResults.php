<?php

namespace App\Filament\Resources\ResultResource\Pages;

use App\Filament\Resources\ResultResource;
use App\Models\Event;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;

class ListResults extends ListRecords
{
    protected static string $resource = ResultResource::class;

    public ?int $cachedEventId = null;

    public function getCachedEventId(): ?int
    {
        if ($this->cachedEventId !== null) {
            return $this->cachedEventId;
        }

        $eventId = request()->query('tableFilters')['event_id'] ?? null;
        if ($eventId) {
            $this->cachedEventId = (int) $eventId;
        }

        return $this->cachedEventId;
    }

    public function table(Table $table): Table
    {
        return ResultResource::table($table)
            ->modifyQueryUsing(function ($query) {
                $eventId = $this->getCachedEventId();
                if ($eventId) {
                    $query->where('event_id', $eventId);
                }
            });
    }

    public function getHeading(): string
    {
        $eventId = $this->getCachedEventId();

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
        $eventId = $this->getCachedEventId();

        return [
            Actions\CreateAction::make()
                ->schema(fn () => ResultResource::getModalForm())
                ->data([
                    'event_id' => $eventId,
                ]),
        ];
    }
}
