<?php

namespace App\Filament\Resources\MarketResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OutcomesRelationManager extends RelationManager
{
    protected static string $relationship = 'outcomes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('outcome_type_id')
                    ->label('Outcome Type')
                    ->relationship('outcomeType', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('participant_id')
                    ->label('Participant')
                    ->relationship('participant', 'name')
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('coef')
                    ->label('Coefficient')
                    ->numeric()
                    ->required()
                    ->minValue(1.01)
                    ->step(0.01),

                Forms\Components\Select::make('result')
                    ->label('Result')
                    ->options([
                        'win' => 'Win',
                        'lose' => 'Lose',
                        'return' => 'Return',
                    ])
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('outcomeType.name')
                    ->label('Type')
                    ->sortable(),

                Tables\Columns\TextColumn::make('participant.name')
                    ->label('Participant')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextInputColumn::make('coef')
                    ->label('Coefficient')
                    ->type('number')
                    ->rules(['required', 'numeric', 'min:1.01'])
                    ->step(0.01),

                Tables\Columns\TextColumn::make('result')
                    ->label('Result')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'win' => 'success',
                        'lose' => 'danger',
                        'return' => 'warning',
                        default => 'gray',
                    })
                    ->placeholder('—'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
