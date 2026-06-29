<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupEventResource\Pages;
use App\Models\GroupEvent;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class GroupEventResource extends \Filament\Resources\Resource
{
    protected static ?string $model = GroupEvent::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar';

    protected static UnitEnum|string|null $navigationGroup = 'Customers';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Group Event';

    protected static ?string $pluralModelLabel = 'Group Events';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Select::make('group_id')
                            ->relationship('group', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('tournament_id')
                            ->relationship('tournament', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('event_id')
                            ->relationship('event', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('group.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tournament.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('event.name')
                    ->sortable(),
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
            ])
            ->actions([
                Actions\EditAction::make(),
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
            'index' => Pages\ListGroupEvents::route('/'),
            'create' => Pages\CreateGroupEvent::route('/create'),
            'edit' => Pages\EditGroupEvent::route('/{record}/edit'),
        ];
    }
}
