<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MarketTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketTemplatePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MarketTemplate');
    }

    public function view(AuthUser $authUser, MarketTemplate $marketTemplate): bool
    {
        return $authUser->can('View:MarketTemplate');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MarketTemplate');
    }

    public function update(AuthUser $authUser, MarketTemplate $marketTemplate): bool
    {
        return $authUser->can('Update:MarketTemplate');
    }

    public function delete(AuthUser $authUser, MarketTemplate $marketTemplate): bool
    {
        return $authUser->can('Delete:MarketTemplate');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MarketTemplate');
    }

    public function restore(AuthUser $authUser, MarketTemplate $marketTemplate): bool
    {
        return $authUser->can('Restore:MarketTemplate');
    }

    public function forceDelete(AuthUser $authUser, MarketTemplate $marketTemplate): bool
    {
        return $authUser->can('ForceDelete:MarketTemplate');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MarketTemplate');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MarketTemplate');
    }

    public function replicate(AuthUser $authUser, MarketTemplate $marketTemplate): bool
    {
        return $authUser->can('Replicate:MarketTemplate');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MarketTemplate');
    }

}