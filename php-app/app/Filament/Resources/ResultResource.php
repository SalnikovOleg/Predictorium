<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResultResource\Pages;
use App\Models\Result;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
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
                ->default(fn () => request()->query('event_id')),
            Forms\Components\Select::make('result_type_id')
                ->relationship('resultType', 'name')
                ->required()
                ->searchable()
                ->preload(),
            Forms\Components\Select::make('participant_id')
                ->relationship('participant', 'name')
                ->searchable()
                ->preload()
                ->nullable(),
            Forms\Components\TextInput::make('time')
                ->numeric()
                ->nullable()
                ->label('Time (seconds)'),
            Forms\Components\Textarea::make('value')
                ->rows(3)
                ->nullable()
                ->json(),
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
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
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
