<?php

namespace App\Filament\Resources;

use App\Enums\TaxonomyType;
use App\Filament\Resources\TaxonomyResource\Pages;
use App\Models\Taxonomy;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class TaxonomyResource extends \Filament\Resources\Resource
{
    protected static ?string $model = Taxonomy::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-folder';

    protected static UnitEnum|string|null $navigationGroup = 'Trading Config';

    protected static ?int $navigationSort = 20;

    protected static ?string $modelLabel = 'Taxonomy';

    protected static ?string $pluralModelLabel = 'Taxonomy';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options(TaxonomyType::class)
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
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
//                Tables\Filters\SelectFilter::make('type')
//                    ->options(TaxonomyType::class),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaxonomies::route('/'),
            'create' => Pages\CreateTaxonomy::route('/create'),
            'edit' => Pages\EditTaxonomy::route('/{record}/edit'),
        ];
    }
}
