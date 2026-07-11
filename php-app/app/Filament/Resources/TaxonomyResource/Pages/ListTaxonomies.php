<?php

namespace App\Filament\Resources\TaxonomyResource\Pages;

use App\Enums\TaxonomyType;
use App\Filament\Resources\TaxonomyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListTaxonomies extends ListRecords
{
    protected static string $resource = TaxonomyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        return [
            ...collect(TaxonomyType::cases())
                ->mapWithKeys(fn (TaxonomyType $type): array => [
                    $type->value => Tab::make($type->label())
                        ->modifyQueryUsing(fn ($query) => $query->where('type', $type)),
                ])
                ->all(),
        ];
    }
}
