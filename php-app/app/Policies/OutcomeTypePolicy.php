<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\OutcomeType;
use Illuminate\Auth\Access\HandlesAuthorization;

class OutcomeTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:OutcomeType');
    }

    public function view(AuthUser $authUser, OutcomeType $outcomeType): bool
    {
        return $authUser->can('View:OutcomeType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:OutcomeType');
    }

    public function update(AuthUser $authUser, OutcomeType $outcomeType): bool
    {
        return $authUser->can('Update:OutcomeType');
    }

    public function delete(AuthUser $authUser, OutcomeType $outcomeType): bool
    {
        return $authUser->can('Delete:OutcomeType');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:OutcomeType');
    }

    public function restore(AuthUser $authUser, OutcomeType $outcomeType): bool
    {
        return $authUser->can('Restore:OutcomeType');
    }

    public function forceDelete(AuthUser $authUser, OutcomeType $outcomeType): bool
    {
        return $authUser->can('ForceDelete:OutcomeType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:OutcomeType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:OutcomeType');
    }

    public function replicate(AuthUser $authUser, OutcomeType $outcomeType): bool
    {
        return $authUser->can('Replicate:OutcomeType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:OutcomeType');
    }

}