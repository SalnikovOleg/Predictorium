<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TournamentConfigResource\Pages;
use App\Models\MarketTemplate;
use App\Models\TournamentConfig;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class TournamentConfigResource extends \Filament\Resources\Resource
{
    protected static ?string $model = TournamentConfig::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static UnitEnum|string|null $navigationGroup = 'Trading Config';

    protected static ?int $navigationSort = 25;

    protected static ?string $modelLabel = 'Tournament Config';

    protected static ?string $pluralModelLabel = 'Tournament Configs';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('rules_json')
                            ->rows(5),
                        Forms\Components\Select::make('market_template_ids')
                            ->options(MarketTemplate::pluck('name', 'id'))
                            ->multiple()
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
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
                //
            ])
            ->recordActions([
                Actions\EditAction::make()->label(''),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTournamentConfigs::route('/'),
            'create' => Pages\CreateTournamentConfig::route('/create'),
            'edit' => Pages\EditTournamentConfig::route('/{record}/edit'),
        ];
    }
}
