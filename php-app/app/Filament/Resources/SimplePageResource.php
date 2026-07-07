<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SimplePageResource\Pages;
use App\Filament\Resources\SimplePageResource\RelationManagers;
use App\Models\SimplePage;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class SimplePageResource extends \Filament\Resources\Resource
{
    protected static ?string $model = SimplePage::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    //protected static UnitEnum|string|null $navigationGroup = 'Structure';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Simple Page';

    protected static ?string $pluralModelLabel = 'Simple Pages';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                        Forms\Components\KeyValue::make('params_json')
                            ->label('Parameters'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                    Actions\ForceDeleteBulkAction::make(),
                    Actions\RestoreBulkAction::make(),
                ]),
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
            'index' => Pages\ListSimplePages::route('/'),
            'create' => Pages\CreateSimplePage::route('/create'),
            'edit' => Pages\EditSimplePage::route('/{record}/edit'),
        ];
    }
}
