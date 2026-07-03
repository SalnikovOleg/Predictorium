<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketResource\Pages;
use App\Models\Event;
use App\Models\Market;
use App\Models\MarketTemplate;
use App\Models\Participant;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class MarketResource extends \Filament\Resources\Resource
{
    protected static ?string $model = Market::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-banknotes';

    protected static UnitEnum|string|null $navigationGroup = 'Trading';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Market';

    protected static ?string $pluralModelLabel = 'Markets';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Hidden::make('event_id')
                            ->default(fn () => request()->query('event_id')),

                        Forms\Components\Select::make('market_template_id')
                            ->label('Market Template')
                            ->options(function (Get $get) {
                                $eventId = $get('event_id') ?? request()->query('event_id');
                                if (! $eventId) {
                                    return [];
                                }

                                $event = Event::with('tournament.config')->find($eventId);
                                if (! $event || ! $event->tournament || ! $event->tournament->config) {
                                    return [];
                                }

                                $templateIds = $event->tournament->config->market_template_ids ?? [];

                                return MarketTemplate::whereIn('id', $templateIds)
                                    ->get()
                                    ->mapWithKeys(fn (MarketTemplate $t) => [
                                        $t->id => $t->name,
                                    ]);
                            })
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(function ($set, $state) {
                                $template = MarketTemplate::with('marketType')->find($state);
                                if ($template) {
                                    $set('market_type_id', $template->market_type_id);
                                    $set('param1', $template->param1);
                                    $set('description', $template->marketType->name);
                                }
                            })
                            ->afterStateHydrated(function ($set, $state) {
                                if ($state) {
                                    $template = MarketTemplate::with('marketType')->find($state);
                                    if ($template) {
                                        $set('market_type_id', $template->market_type_id);
                                        $set('param1', $template->param1);
                                    }
                                }
                            })
                            ->required(),

                        Forms\Components\Hidden::make('market_type_id'),

                        Forms\Components\TextInput::make('description')
                            ->label('Description')
                            ->maxLength(255)
                            ->readOnly(),

                        Forms\Components\Select::make('participant_id')
                            ->label('Participant')
                            ->options(function (Get $get) {
                                $eventId = $get('event_id') ?? request()->query('event_id');
                                if (! $eventId) {
                                    return [];
                                }

                                return Participant::whereHas('events', fn ($q) => $q->where('events.id', $eventId))
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->visible(fn (Get $get) => (int) $get('market_type_id') === 2)
                            ->afterStateUpdated(function ($set, $get, $state) {
                                $template = MarketTemplate::find($get('market_template_id'));
                                if ($template && $state) {
                                    $participant = Participant::find($state);
                                    $set('description', $template->name . ' — ' . $participant->name);
                                }
                            }),

                        Forms\Components\Select::make('participant_ids')
                            ->label(fn (Get $get) => 'Select Participants (max ' . ($get('param1') ?? '?') . ')')
                            ->options(function (Get $get) {
                                $eventId = $get('event_id') ?? request()->query('event_id');
                                if (! $eventId) {
                                    return [];
                                }

                                return Participant::whereHas('events', fn ($q) => $q->where('events.id', $eventId))
                                    ->pluck('name', 'id');
                            })
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->visible(fn (Get $get) => (int) $get('market_type_id') === 3),

                        Forms\Components\Select::make('participant_a_id')
                            ->label('Participant A')
                            ->options(function (Get $get) {
                                $eventId = $get('event_id') ?? request()->query('event_id');
                                if (! $eventId) {
                                    return [];
                                }

                                return Participant::whereHas('events', fn ($q) => $q->where('events.id', $eventId))
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->visible(fn (Get $get) => (int) $get('market_type_id') === 4),

                        Forms\Components\Select::make('participant_b_id')
                            ->label('Participant B')
                            ->options(function (Get $get) {
                                $eventId = $get('event_id') ?? request()->query('event_id');
                                if (! $eventId) {
                                    return [];
                                }

                                return Participant::whereHas('events', fn ($q) => $q->where('events.id', $eventId))
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->visible(fn (Get $get) => (int) $get('market_type_id') === 4),

                        Forms\Components\Hidden::make('param1'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('marketTemplate.name')
                    ->label('Template')
                    ->sortable(),
                Tables\Columns\TextColumn::make('marketTemplate.marketType.name')
                    ->label('Type'),
                Tables\Columns\TextColumn::make('description')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('outcomes_count')
                    ->counts('outcomes')
                    ->label('Outcomes')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
//                Tables\Filters\SelectFilter::make('event_id')
//                    ->relationship('event', 'name')
//                    ->label('Event'),
            ])
            ->actions([
                //Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarkets::route('/'),
            'create' => Pages\CreateMarket::route('/create'),
            'edit' => Pages\EditMarket::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with(['marketTemplate', 'marketTemplate.marketType', 'outcomes']);
    }
}
