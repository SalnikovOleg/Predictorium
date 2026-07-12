<?php

namespace App\Filament\Resources;

use App\Enums\EventStatus;
use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class EventResource extends \Filament\Resources\Resource
{
    protected static ?string $model = Event::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static UnitEnum|string|null $navigationGroup = 'Trading';

    protected static ?int $navigationSort = 32;

    protected static ?string $modelLabel = 'Event';

    protected static ?string $pluralModelLabel = 'Events';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('tournament_id')
                            ->relationship('tournament', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, $set) => $set('participants', [])),
                        Section::make()
                            ->schema([
                                Forms\Components\DateTimePicker::make('start_date')
                                    ->format('Y-m-d H:i'),
                                Forms\Components\DateTimePicker::make('end_date')
                                    ->format('Y-m-d H:i'),
                                Forms\Components\Select::make('status')
                                    ->options(EventStatus::class)
                                    ->default(EventStatus::Draft)
                                    ->required(),
                                Forms\Components\TextInput::make('ext_id')
                                    ->label('External ID')
                                    ->maxLength(50),
                            ])->columns(2)
                    ]),
                Section::make('Participants')
                    ->schema([
                        Forms\Components\Select::make('participants')
                            ->relationship('participants', 'name')
                            ->label('Event Participants')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn ($get) => \App\Models\Participant::query()
                                ->with('taxonomy')
                                ->where('taxonomy_id', \App\Models\Tournament::find($get('tournament_id'))?->taxonomy_id)
                                ->get()
                                ->mapWithKeys(fn ($p) => [$p->id => "{$p->name} ({$p->taxonomy->name})"])),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tournament.name')
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime('Y-m-d H:i')
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime('Y-m-d H:i')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(EventStatus::class),
                Tables\Filters\SelectFilter::make('tournament_id')
                    ->relationship('tournament', 'name')
                    ->label('Tournament'),
            ])
            ->recordActions([
                //Actions\EditAction::make(),
                Actions\Action::make('markets')
                    ->label('Markets')
                    ->icon('heroicon-o-banknotes')
                    ->url(fn (Event $record): string => MarketResource::getUrl('index', ['tableFilters' => ['event_id' => $record->id]])),
                Actions\Action::make('results')
                    ->label('Results')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (Event $record): string => ResultResource::getUrl('index', ['tableFilters' => ['event_id' => $record->id]])),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ContentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
