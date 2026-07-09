<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WidgetsPageResource\Pages;
use App\Models\Widget;
use App\Models\WidgetsPage;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class WidgetsPageResource extends \Filament\Resources\Resource
{
    protected static ?string $model = WidgetsPage::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-window';

    protected static UnitEnum|string|null $navigationGroup = 'Layouts';

    protected static ?int $navigationSort = 12;

    protected static ?string $modelLabel = 'Structure';

    protected static ?string $pluralModelLabel = 'Widgets Pages';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Select::make('widget_id')
                            ->relationship('widget', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('model_type')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('model_id')
                            ->required()
                            ->numeric(),
                        Forms\Components\KeyValue::make('params')
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
                Tables\Columns\TextColumn::make('widget.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('model_type')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('model_id')
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
            'index' => Pages\ListWidgetsPages::route('/'),
            'create' => Pages\CreateWidgetsPage::route('/create'),
            'edit' => Pages\EditWidgetsPage::route('/{record}/edit'),
        ];
    }
}
