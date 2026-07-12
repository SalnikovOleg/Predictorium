<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketTemplateResource\Pages;
use App\Models\MarketTemplate;
use App\Models\MarketType;
use App\Models\OutcomeType;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class MarketTemplateResource extends \Filament\Resources\Resource
{
    protected static ?string $model = MarketTemplate::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static UnitEnum|string|null $navigationGroup = 'Trading Config';

    protected static ?int $navigationSort = 24;

    protected static ?string $modelLabel = 'Market Template';

    protected static ?string $pluralModelLabel = 'Market Templates';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('market_type_id')
                            ->relationship('marketType', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('outcome_template_ids')
                            ->options(OutcomeType::pluck('name', 'id'))
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('marketType.name')
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
            ])
            ->toolbarActions([
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketTemplates::route('/'),
        ];
    }
}
