<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StakeResource\Pages;
use App\Models\Stake;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class StakeResource extends \Filament\Resources\Resource
{
    protected static ?string $model = Stake::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static UnitEnum|string|null $navigationGroup = 'Customers';

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'Stake';

    protected static ?string $pluralModelLabel = 'Stakes';

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
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('event_id')
                            ->relationship('event', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('outcome_id')
                            ->relationship('outcome', 'id')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('sum_in')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('coef')
                            ->required()
                            ->numeric(),
                        Forms\Components\Select::make('result')
                            ->options([
                                'win' => 'Win',
                                'lose' => 'Lose',
                                'return' => 'Return',
                            ]),
                        Forms\Components\TextInput::make('sum_out')
                            ->numeric(),
                    ])->columns(2),
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
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('event.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('outcome.id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sum_in')
                    ->sortable(),
                Tables\Columns\TextColumn::make('coef')
                    ->sortable(),
                Tables\Columns\TextColumn::make('result')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sum_out')
                    ->sortable(),
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
            ])
            ->recordActions([
                Actions\EditAction::make(),
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
            'index' => Pages\ListStakes::route('/'),
            'create' => Pages\CreateStake::route('/create'),
            'edit' => Pages\EditStake::route('/{record}/edit'),
        ];
    }
}
