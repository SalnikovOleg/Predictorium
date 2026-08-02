---
name: filament-resource-developer
description: Creates and modifies Filament v5.6 Resources following Predictorium conventions: Trading folder structure, slug-based routing, FileUpload icons, reactive dependent selects, ext_id/params fields, and RelationManagers.
triggers: [filament resource, create resource, edit resource, filament form, filament table, relationmanager, fileupload icon, reactive select]
---

# Filament Resource Developer (Predictorium)

## Purpose

Build and maintain Filament v5.6 admin Resources for the Predictorium sports betting platform, adhering to project-specific conventions for folder structure, routing, form fields, and relationships.

## When to Use

- Creating new Filament Resources for Category, Tournament, Event, Market, Participant, TournamentConfig, or any Trading entity
- Adding RelationManagers for polymorphic or hasMany relationships
- Modifying forms/tables with reactive dependent selects
- Adding icon uploads, ext_id fields, or params KeyValue fields
- Implementing slug-based routing (not ID-based)

## Project Conventions (Must Follow)

### 1. Folder Structure

All Trading-related Controllers, Services, Repositories live under `Trading/` subfolders:
```
app/Http/Controllers/Trading/
app/Services/Trading/
app/Repositories/Trading/
```

Filament Resources live at:
```
app/Filament/Resources/<Entity>Resource.php
app/Filament/Resources/<Entity>Resource/Pages/
app/Filament/Resources/<Entity>Resource/RelationManagers/
```

### 2. Slug-Based Routing

- All entity links use slugs, not IDs
- Resource `getRecordRouteBinding()` resolves slug → model
- Table actions use `record.slug` for URLs
- API endpoints accept slug, resolve internally

```php
// In Resource
public static function getRecordRouteBinding(string $value): Model
{
    return static::getModel()::where('slug', $value)->firstOrFail();
}
```

### 3. Date Format

All date outputs: `yyyy-mm-dd HH:ii` (e.g., `2026-03-01 14:30`)
```php
TextColumn::make('start_date')->format('Y-m-d H:i')
```

### 4. Standard Form Fields

**ext_id** (External ID) — on Tournament, Event:
```php
TextInput::make('ext_id')
    ->label('External ID')
    ->maxLength(255)
    ->columnSpan(1)
```

**params** (JSON array) — on TournamentConfig, Tournament:
```php
KeyValue::make('params')
    ->label('Params')
    ->reorderable()
    ->columnSpanFull()
```

**icon** (FileUpload) — on Category, Tournament, Participant:
```php
FileUpload::make('icon')
    ->image()
    ->disk('public')
    ->directory('<entity>/icons')  // categories/icons, tournaments/icons, participants/icons
    ->imageEditor()
    ->columnSpanFull()
```
*Model must have `'icon'` in `$fillable`. Migration already has nullable string `icon` column.*

**description** (Textarea) — full width:
```php
Section::make()->schema([
    Textarea::make('description')->columnSpanFull(),
])->columnSpanFull()
```

### 5. Reactive Dependent Selects

Use `$get` / `$set` for cascading dropdowns (e.g., Category → Taxonomy → Tournament):

```php
Select::make('category_id')
    ->label('Category')
    ->options(Category::pluck('name', 'id'))
    ->reactive()
    ->afterStateUpdated(fn ($set) => $set('taxonomy_id', null)),

Select::make('taxonomy_id')
    ->label('Community/Country')
    ->options(fn ($get) => Taxonomy::where('type', Category::find($get('category_id'))?->taxonomy_type?->value)
        ->pluck('name', 'id'))
    ->disabled(fn ($get) => !$get('category_id'))
    ->required(fn ($get) => (bool)$get('category_id')),
```

**Key rules:**
- Parent select: `->reactive()->afterStateUpdated(fn ($set) => $set('child_field', null))`
- Child select: `->options(fn ($get) => ...)` with `$get('parent_field')`
- Child select: `->disabled(fn ($get) => !$get('parent_field'))`

### 6. belongsToMany with Filtered Options

For many-to-many (e.g., Event → Participants filtered by Tournament taxonomy):

```php
// Event model has: participants() belongsToMany with pivot event_participants
Select::make('participants')
    ->label('Participants')
    ->multiple()
    ->relationship('participants', 'name')  // REQUIRED for pivot sync
    ->options(fn ($get) => {
        $tournament = Tournament::find($get('tournament_id'));
        $taxonomyIds = $tournament?->config?->taxonomy_ids ?? [];
        return Participant::whereIn('taxonomy_id', $taxonomyIds)
            ->get()
            ->mapWithKeys(fn ($p) => [$p->id => "{$p->name} ({$p->taxonomy->name})"]);
    })
    ->preload()
    ->searchable(),
```

**Critical:** Always use `->relationship('relationName', 'labelColumn')` — custom `options()` alone does NOT save to pivot table.

### 7. RelationManager Pattern

For polymorphic content (Pages) or hasMany (Outcomes):

```
app/Filament/Resources/<Parent>Resource/RelationManagers/<Name>RelationManager.php
```

Register in parent Resource:
```php
public static function getRelations(): array
{
    return [ContentsRelationManager::class, OutcomesRelationManager::class];
}
```

RelationManager structure:
```php
class ContentsRelationManager extends RelationManager
{
    protected static string $relationship = 'contents';  // matches morphMany name
    
    public function form(Form $form): Form
    {
        return $form->schema([
            // Repeater for each language (en, uk)
            Repeater::make('contents')
                ->schema([
                    Select::make('lang')
                        ->options(Language::cases())
                        ->native(false),
                    TextInput::make('title')->required(),
                    RichEditor::make('content'),
                ])
                ->columns(2)
                ->defaultItems([
                    ['lang' => 'en'],
                    ['lang' => 'uk'],
                ]),
        ]);
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('lang')->badge(),
                TextColumn::make('title'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
```

### 8. Table Actions (Filament v5.6 API)

Use `recordActions()` and `toolbarActions()` (NOT v3's `actions()`/`bulkActions()`):

```php
->recordActions([
    EditAction::make(),
    ViewAction::make(),
    DeleteAction::make(),
])
->toolbarActions([
    CreateAction::make(),
])
```

### 9. Common Resource Sections Layout

```php
public function form(Form $form): Form
{
    return $form->schema([
        Section::make('Basic Info')->schema([
            TextInput::make('name')->required(),
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            // ext_id if applicable
        ])->columns(2),
        
        Section::make('Description')->schema([
            Textarea::make('description')->columnSpanFull(),
        ])->columnSpanFull(),
        
        Section::make('Relations')->schema([
            Select::make('category_id')->reactive()->..,
            Select::make('taxonomy_id')->options(fn ($get) => ...),
            // config_id, status, dates
        ])->columns(2),
    ]);
}
```

## Procedure

1. **Create Migration** (if new entity): JSON columns cast to `array`, proper indexes, slug unique
2. **Update Model**: `$fillable`, casts (`array` for JSON), relationships (`belongsTo`, `belongsToMany`, `morphMany`)
3. **Create Resource**: `php artisan make:filament-resource <Entity>Resource --generate` (then customize)
4. **Configure Form**: Apply conventions above (ext_id, params, icon, reactive selects, relationship())
5. **Configure Table**: Slug-based actions, date format `Y-m-d H:i`, recordActions/toolbarActions
6. **Add RelationManagers** if needed: `RelationManagers/` dir, register in `getRelations()`
7. **Register Navigation**: `protected static ?string $navigationGroup = 'Trading';`
8. **Test in Admin**: `/admin/<entity>` — verify create, edit, list, relations

## Quality Bar (Self-Check)

- [ ] Resource in `app/Filament/Resources/` with `Trading` navigation group
- [ ] Slug-based routing via `getRecordRouteBinding()`
- [ ] Dates formatted `Y-m-d H:i`
- [ ] Icon uses FileUpload with correct directory
- [ ] Reactive selects clear child on parent change
- [ ] belongsToMany uses `->relationship()` + filtered `->options()`
- [ ] RelationManagers registered in `getRelations()`
- [ ] Table uses `recordActions()` / `toolbarActions()`
- [ ] Model has all form fields in `$fillable`

## Anti-patterns

- Using ID-based routes or `record.id` in table actions
- TextInput for icon fields (use FileUpload)
- Custom options() without relationship() on belongsToMany
- Missing `->reactive()` on parent selects
- Using v3 `actions()` / `bulkActions()` instead of `recordActions()` / `toolbarActions()`
- Hardcoding taxonomy filters instead of using tournament.config.taxonomy_ids
- Forgetting to add `'icon'` to model `$fillable`

## Example

**Input**: "Create Filament Resource for TournamentConfig with taxonomy_ids multi-select, icon upload, and params KeyValue."

**Output**:
- Migration: `taxonomy_ids` JSON column, `icon` string, `params` JSON
- Model: `$fillable` includes all three, casts `['taxonomy_ids' => 'array', 'params' => 'array']`
- Resource: Form with taxonomy_ids Select (multiple, Taxonomy options), icon FileUpload (tournament_configs/icons), params KeyValue
- Table: name, slug, icon preview, taxonomy badges, dates formatted