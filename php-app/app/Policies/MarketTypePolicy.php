<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MarketType;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MarketType');
    }

    public function view(AuthUser $authUser, MarketType $marketType): bool
    {
        return $authUser->can('View:MarketType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MarketType');
    }

    public function update(AuthUser $authUser, MarketType $marketType): bool
    {
        return $authUser->can('Update:MarketType');
    }

    public function delete(AuthUser $authUser, MarketType $marketType): bool
    {
        return $authUser->can('Delete:MarketType');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MarketType');
    }

    public function restore(AuthUser $authUser, MarketType $marketType): bool
    {
        return $authUser->can('Restore:MarketType');
    }

    public function forceDelete(AuthUser $authUser, MarketType $marketType): bool
    {
        return $authUser->can('ForceDelete:MarketType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MarketType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MarketType');
    }

    public function replicate(AuthUser $authUser, MarketType $marketType): bool
    {
        return $authUser->can('Replicate:MarketType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MarketType');
    }

}