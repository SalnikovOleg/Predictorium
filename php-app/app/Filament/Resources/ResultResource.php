<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResultResource\Pages;
use App\Models\Result;
use App\Models\ResultType;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class ResultResource extends \Filament\Resources\Resource
{
    protected static ?string $model = Result::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static UnitEnum|string|null $navigationGroup = 'Trading';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Result';

    protected static ?string $pluralModelLabel = 'Results';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema(self::getModalForm());
    }

    public static function getModalForm(?Result $record = null): array
    {
        return [
            Forms\Components\Hidden::make('event_id')
                ->default(fn () => request()->query('tableFilters')['event_id'] ?? null)
                ->reactive(),

            Forms\Components\Select::make('result_type_id')
                ->label('Result Type')
                ->relationship('resultType', 'name')
                ->required()
                ->searchable()
                ->preload()
                ->reactive()
                ->disabled(fn (?Result $record) => $record !== null)
                ->afterStateUpdated(fn ($set) => [
                    $set('participant_id', null),
                    $set('value', null),
                ]),

            // Single participant (for value_type = 'participant')
            Forms\Components\Select::make('participant_id')
                ->label('Participant')
                ->options(function (Get $get) {
                    $resultTypeId = $get('result_type_id');
                    if (! $resultTypeId) {
                        return [];
                    }
                    $resultType = ResultType::find($resultTypeId);
                    if (! $resultType || $resultType->value_type !== 'participant') {
                        return [];
                    }
                    $eventId = $get('event_id');
                    if (! $eventId) {
                        return [];
                    }

                    return \App\Models\Event::find($eventId)
                        ?->participants()
                        ->pluck('participants.name', 'participants.id')
                        ->toArray() ?? [];
                })
                ->searchable()
                ->preload()
                ->nullable()
                ->visible(function (Get $get) {
                    $resultTypeId = $get('result_type_id');
                    if (! $resultTypeId) {
                        return false;
                    }
                    $resultType = ResultType::find($resultTypeId);

                    return $resultType && $resultType->value_type === 'participant';
                }),

            // Repeater for positions (for value_type = 'positions')
            Forms\Components\Repeater::make('value')
                ->label('Positions')
                ->schema([
                    Forms\Components\TextInput::make('place')
                        ->label('Place')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->maxValue(999),
                    Forms\Components\Select::make('participant_id')
                        ->label('Participant')
                        ->options(function (Get $get) {
                            $eventId = $get('../../event_id');
                            if (! $eventId) {
                                return [];
                            }

                            return \App\Models\Event::find($eventId)
                                ?->participants()
                                ->pluck('participants.name', 'participants.id')
                                ->toArray() ?? [];
                        })
                        ->searchable()
                        ->preload()
                        ->required(),
                ])
                ->columns(2)
                ->defaultItems(1)
                ->addable()
                ->deletable()
                ->visible(function (Get $get) {
                    $resultTypeId = $get('result_type_id');
                    if (! $resultTypeId) {
                        return false;
                    }
                    $resultType = ResultType::find($resultTypeId);

                    return $resultType && $resultType->value_type === 'positions';
                }),

            Forms\Components\TextInput::make('time')
                ->numeric()
                ->nullable()
                ->label('Time (seconds)'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('resultType.name')
                    ->label('Result Type'),
                Tables\Columns\TextColumn::make('participant.name'),
                Tables\Columns\TextColumn::make('time'),
                Tables\Columns\TextColumn::make('value')
                    ->limit(10),
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
                Tables\Filters\SelectFilter::make('result_type_id')
                    ->relationship('resultType', 'name')
                    ->label('Result Type'),
            ])
            ->recordActions([
                Actions\EditAction::make()
                    ->schema(fn (Result $record): array => self::getModalForm($record)),
            ])
            ->toolbarActions([
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
            'index' => Pages\ListResults::route('/'),
        ];
    }
}
