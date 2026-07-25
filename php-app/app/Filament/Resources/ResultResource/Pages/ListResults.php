<?php

namespace App\Filament\Resources\ResultResource\Pages;

use App\Filament\Resources\EventResource;
use App\Filament\Resources\ResultResource;
use App\Models\Event;
use App\Services\Trading\OutcomeResultService;
use Filament\Actions;
use Filament\Notifications\Notification;
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
            Actions\Action::make('backToEvent')
                ->label('Back to Event')
                ->icon('heroicon-o-arrow-left')
                ->url(fn () => $eventId ? EventResource::getUrl('edit', ['record' => $eventId]) : null)
                ->visible(fn () => $eventId !== null),

            Actions\Action::make('calculate')
                ->label('Calculate')
                ->icon('heroicon-o-calculator')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Calculate Results')
                ->modalDescription('This will determine win/lose for all outcomes based on the recorded results. Continue?')
                ->visible(fn () => $eventId !== null)
                ->action(function () use ($eventId) {
                    try {
                        app(OutcomeResultService::class)
                            ->calculateForEvent($eventId);

                        Notification::make()
                            ->title('Results calculated')
                            ->body('All outcomes have been updated based on the recorded results.')
                            ->success()
                            ->send();
                    } catch (\InvalidArgumentException $e) {
                        Notification::make()
                            ->title('Calculation failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Actions\CreateAction::make()
                ->schema(fn () => ResultResource::getModalForm(eventId: $eventId))
                ->data([
                    'event_id' => $eventId,
                ]),
        ];
    }
}
