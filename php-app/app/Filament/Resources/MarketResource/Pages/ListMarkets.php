<?php

namespace App\Filament\Resources\MarketResource\Pages;

use App\Filament\Resources\EventResource;
use App\Filament\Resources\MarketResource;
use App\Models\Event;
use App\Services\Trading\MarketService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;

class ListMarkets extends ListRecords
{
    protected static string $resource = MarketResource::class;

    public ?int $eventId = null;

    public function mount(): void
    {
        parent::mount();

        $this->eventId = (int) (request()->query('tableFilters')['event_id'] ?? 0) ?: null;
    }

    public function getHeading(): string
    {
        if ($this->eventId) {
            $event = Event::find($this->eventId);
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
                if ($this->eventId) {
                    $query->where('event_id', $this->eventId);
                }
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('backToEvent')
                ->label('Back to Event')
                ->icon('heroicon-o-arrow-left')
                ->url(fn () => $this->eventId ? EventResource::getUrl('edit', ['record' => $this->eventId]) : null)
                ->visible(fn () => $this->eventId !== null),

            Actions\CreateAction::make()
                ->url(fn () => static::getResource()::getUrl('create', [
                    'event_id' => $this->eventId,
                ])),

            Actions\Action::make('createDefaultsMarket')
                ->label('Add all default')
                ->icon('heroicon-o-sparkles')
                ->requiresConfirmation()
                ->modalHeading('Create Default Markets')
                ->modalDescription('Create markets for all templates configured in this tournament. Existing markets will be skipped.')
                ->action(function () {
                    if (! $this->eventId) {
                        Notification::make()
                            ->title('No event selected')
                            ->warning()
                            ->send();

                        return;
                    }

                    $service = app(MarketService::class);
                    $count = $service->createDefaultsMarket($this->eventId);

                    Notification::make()
                        ->title("Created {$count} default market(s)")
                        ->success()
                        ->send();
                })
                ->visible(fn () => filled($this->eventId)),
        ];
    }
}
