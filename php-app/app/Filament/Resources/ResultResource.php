<?php

namespace App\Filament\Resources;

use App\Enums\ValueType;
use App\Filament\Resources\ResultResource\Pages;
use App\Models\Event;
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

    public static function getModalForm(?Result $record = null, ?int $eventId = null): array
    {
        $resolvedEventId = $eventId ?? $record?->event_id;

        return [
            Forms\Components\Hidden::make('event_id')
                ->default($resolvedEventId),

            Forms\Components\Select::make('result_type_id')
                ->label('Result Type')
                ->relationship('resultType', 'name', fn ($query) => $query
                    ->when($resolvedEventId, function ($q) use ($resolvedEventId, $record) {
                        $category = Event::find($resolvedEventId)?->tournament?->category;
                        if ($category) {
                            $q->where('category_id', $category->id);
                        }
                        $usedTypeIds = Result::where('event_id', $resolvedEventId)
                            ->pluck('result_type_id');
                        if ($record) {
                            $usedTypeIds = $usedTypeIds->reject($record->result_type_id);
                        }
                        if ($usedTypeIds->isNotEmpty()) {
                            $q->whereNotIn('id', $usedTypeIds);
                        }
                    })
                )
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
                ->options(function (Get $get) use ($resolvedEventId) {
                    $resultTypeId = $get('result_type_id');
                    if (! $resultTypeId) {
                        return [];
                    }
                    $resultType = ResultType::find($resultTypeId);
                    if (! $resultType || $resultType->value_type !== ValueType::Participant) {
                        return [];
                    }

                    return self::loadParticipantOptions($resolvedEventId);
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

                    return $resultType && $resultType->value_type === ValueType::Participant;
                }),

            // Repeater for positions (for value_type = 'positions')
            // Array index = place (0 = 1st, 1 = 2nd, ...)
            // Model setAttribute converts [{participant_id: X}] → [X] on save
            Forms\Components\Repeater::make('value')
                ->label('Positions')
                ->schema([
                    Forms\Components\Select::make('participant_id')
                        ->label('Participant')
                        ->options(function (Get $get) use ($resolvedEventId) {
                            return self::loadParticipantOptions($resolvedEventId);
                        })
                        ->searchable()
                        ->preload()
                        ->required(),
                ])
                ->columns(1)
                ->defaultItems(1)
                ->addable()
                ->deletable()
                ->reorderable()
                ->afterStateHydrated(function (Forms\Components\Repeater $component, ?Result $record) {
                    $value = $record?->value;
                    if (is_array($value) && !empty($value) && is_int($value[0] ?? null)) {
                        $component->state(array_map(fn ($id) => ['participant_id' => $id], $value));
                    }
                })
                ->visible(function (Get $get) {
                    $resultTypeId = $get('result_type_id');
                    if (! $resultTypeId) {
                        return false;
                    }
                    $resultType = ResultType::find($resultTypeId);

                    return $resultType && $resultType->value_type === ValueType::Positions;
                }),

            // Score select (for value_type = 'score')
            Forms\Components\Select::make('score')
                ->label('Score')
                ->options(function () {
                    return \App\Models\OutcomeType::where('id', '>=', 4)
                        ->where('id', '<=', 52)
                        ->pluck('name', 'name')
                        ->toArray();
                })
                ->searchable()
                ->preload()
                ->required()
                ->visible(function (Get $get) {
                    $resultTypeId = $get('result_type_id');
                    if (! $resultTypeId) {
                        return false;
                    }
                    $resultType = ResultType::find($resultTypeId);

                    return $resultType && $resultType->value_type === ValueType::Score;
                })
                ->afterStateHydrated(function (Forms\Components\Select $component, ?Result $record) {
                    if ($record && $record->value && isset($record->value['score'])) {
                        $component->state($record->value['score']);
                    }
                })
                ->dehydrated(fn ($state) => $state !== null)
                ->saveRelationshipsUsing(function (?Result $record, $state) {
                    if ($record && $state) {
                        $record->update(['value' => ['score' => $state]]);
                    }
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
                    ->schema(fn (Result $record): array => self::getModalForm($record, $record->event_id)),
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

    private static function loadParticipantOptions(?int $eventId): array
    {
        if (! $eventId) {
            return [];
        }

        return \App\Models\Event::find($eventId)
            ?->participants()
            ->pluck('participants.name', 'participants.id')
            ->toArray() ?? [];
    }
}
