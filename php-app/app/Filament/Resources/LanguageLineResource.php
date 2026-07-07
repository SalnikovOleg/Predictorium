<?php

namespace App\Filament\Resources;

use App\Enums\Language;
use App\Filament\Resources\LanguageLineResource\Pages;
use BackedEnum;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\TranslationLoader\LanguageLine;

class LanguageLineResource extends \Filament\Resources\Resource
{
    protected static ?string $model = LanguageLine::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-language';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Language Line';

    protected static ?string $pluralModelLabel = 'Language Lines';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('group')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->maxLength(255),
                    ]),
                Section::make('')
                    ->schema([
                        Forms\Components\Repeater::make('text')
                            ->schema([
                                Forms\Components\TextInput::make('language')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(1),
                                Forms\Components\Textarea::make('value')
                                    ->rows(1)
                                    ->columnSpan(2),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->addable(false)
                            ->deletable(false)
                            ->afterStateHydrated(function ($component, $state) {
                                if (! is_array($state)) {
                                    $state = [];
                                }

                                $hydrated = collect(Language::cases())
                                    ->map(fn (Language $lang) => [
                                        'language' => $lang->value,
                                        'value' => $state[$lang->value] ?? '',
                                    ])
                                    ->values()
                                    ->all();

                                $component->state($hydrated);
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('group')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')
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
                Tables\Filters\SelectFilter::make('group')
                    ->options(fn () => static::getPluckOptions()),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
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
            'index' => Pages\ListLanguageLines::route('/'),
//            'create' => Pages\CreateLanguageLine::route('/create'),
//            'edit' => Pages\EditLanguageLine::route('/{record}/edit'),
        ];
    }

    protected static function getPluckOptions(): array
    {
        return LanguageLine::query()
            ->distinct()
            ->pluck('group', 'group')
            ->toArray();
    }
}
