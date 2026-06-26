<?php

namespace App\Filament\Resources\OutcomeTemplateResource\Pages;

use App\Filament\Resources\OutcomeTemplateResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateOutcomeTemplate extends CreateRecord
{
    protected static string $resource = OutcomeTemplateResource::class;

    protected Width|string|null $maxContentWidth = '6xl';
}
