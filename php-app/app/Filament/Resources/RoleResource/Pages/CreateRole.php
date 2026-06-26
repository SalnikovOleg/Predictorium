<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected Width|string|null $maxContentWidth = '6xl';
}
