<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TournamentConfig;
use Illuminate\Auth\Access\HandlesAuthorization;

class TournamentConfigPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TournamentConfig');
    }

    public function view(AuthUser $authUser, TournamentConfig $tournamentConfig): bool
    {
        return $authUser->can('View:TournamentConfig');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TournamentConfig');
    }

    public function update(AuthUser $authUser, TournamentConfig $tournamentConfig): bool
    {
        return $authUser->can('Update:TournamentConfig');
    }

    public function delete(AuthUser $authUser, TournamentConfig $tournamentConfig): bool
    {
        return $authUser->can('Delete:TournamentConfig');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TournamentConfig');
    }

    public function restore(AuthUser $authUser, TournamentConfig $tournamentConfig): bool
    {
        return $authUser->can('Restore:TournamentConfig');
    }

    public function forceDelete(AuthUser $authUser, TournamentConfig $tournamentConfig): bool
    {
        return $authUser->can('ForceDelete:TournamentConfig');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TournamentConfig');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TournamentConfig');
    }

    public function replicate(AuthUser $authUser, TournamentConfig $tournamentConfig): bool
    {
        return $authUser->can('Replicate:TournamentConfig');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TournamentConfig');
    }

}