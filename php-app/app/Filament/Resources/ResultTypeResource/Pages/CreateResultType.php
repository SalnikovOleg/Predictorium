<?php

namespace App\Filament\Resources\ResultTypeResource\Pages;

use App\Filament\Resources\ResultTypeResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateResultType extends CreateRecord
{
    protected static string $resource = ResultTypeResource::class;

    protected Width|string|null $maxContentWidth = '6xl';
}
