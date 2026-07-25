<?php

namespace App\Filament\Resources\MarketResource\RelationManagers;

use App\Enums\OutcomeResult;
use App\Models\Outcome;
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
                    ->disabled(fn (?Outcome $record) => $record !== null)
                    ->required(),

                Forms\Components\Select::make('participant_id')
                    ->label('Participant')
                    ->relationship('participant', 'name')
                    ->searchable()
                    ->disabled(fn (?Outcome $record) => $record !== null)
                    ->preload(),

                Forms\Components\TextInput::make('coef')
                    ->label('Coefficient')
                    ->numeric()
                    ->default(1)
                    ->minValue(1.0)
                    ->step(0.01),

                Forms\Components\Select::make('result')
                    ->label('Result')
                    ->options(OutcomeResult::class)
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('id')
                    ->sortable(),
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
                    ->rules(['required', 'numeric', 'min:1.0'])
                    ->step(0.01),

                Tables\Columns\SelectColumn::make('result')
                    ->label('Result')
                    ->options(OutcomeResult::class)
                    ->placeholder('—'),
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
