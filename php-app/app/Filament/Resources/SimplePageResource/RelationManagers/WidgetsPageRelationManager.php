<?php

namespace App\Filament\Resources\SimplePageResource\RelationManagers;

use App\Models\Widget;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class WidgetsPageRelationManager extends RelationManager
{
    protected static string $relationship = 'widgets';

    protected static ?string $title = 'Widgets';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('widget_id')
                    ->options(fn () => Widget::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\KeyValue::make('params')
                    ->label('Parameters'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('widget.name')
            ->columns([
                Tables\Columns\TextColumn::make('widget.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('params')
                    ->limitList(3)
                    ->expandableLimitedList(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->recordActions([
                Actions\EditAction::make()->label(''),
                Actions\DeleteAction::make()->label(''),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
