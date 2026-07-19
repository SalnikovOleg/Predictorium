<?php

namespace App\Filament\Resources;

use App\Enums\TournamentStatus;
use App\Filament\Resources\TournamentResource\Pages;
use App\Models\Tournament;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class TournamentResource extends \Filament\Resources\Resource
{
    protected static ?string $model = Tournament::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-trophy';

    protected static UnitEnum|string|null $navigationGroup = 'Trading';

    protected static ?int $navigationSort = 31;

    protected static ?string $modelLabel = 'Tournament';

    protected static ?string $pluralModelLabel = 'Tournaments';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set) {
                                $set('slug', Str::slug($state));
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('icon')
                            ->image()
                            ->disk('public')
                            ->directory('tournaments/icons')
                            ->imageEditor()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\KeyValue::make('params')
                            ->label('Params')
                            ->reorderable(),
                    ]),
                Section::make()
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, $set) => $set('taxonomy_id', null)),
                        Forms\Components\Select::make('taxonomy_id')
                            ->label('Community/country')
                            ->relationship('taxonomy', 'name')
                            ->options(fn ($get) => \App\Models\Taxonomy::query()
                                ->where('type', \App\Models\Category::find($get('category_id'))?->taxonomy_type)
                                ->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('config_id')
                            ->relationship('config', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Tournament Config'),
                        Forms\Components\Select::make('status')
                            ->options(TournamentStatus::class)
                            ->default(TournamentStatus::Draft)
                            ->required(),
                        Forms\Components\DateTimePicker::make('start_date')
                            ->format('Y-m-d H:i'),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->format('Y-m-d H:i'),
                        Forms\Components\TextInput::make('ext_id')
                            ->label('External ID')
                            ->maxLength(255),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('config.name')
                    ->label('Config')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('status')
                    ->options(TournamentStatus::class),
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),
            ])
            ->recordActions([
                Actions\EditAction::make()->label(''),
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
            'index' => Pages\ListTournaments::route('/'),
            'create' => Pages\CreateTournament::route('/create'),
            'edit' => Pages\EditTournament::route('/{record}/edit'),
        ];
    }
}
