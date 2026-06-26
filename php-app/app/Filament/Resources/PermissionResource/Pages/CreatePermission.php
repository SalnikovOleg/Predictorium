<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;

    protected Width|string|null $maxContentWidth = '6xl';
}
