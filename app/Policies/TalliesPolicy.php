<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Tallies;
use App\Models\User;

class TalliesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Tallies');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tallies $tallies): bool
    {
        return $user->checkPermissionTo('view Tallies');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Tallies');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tallies $tallies): bool
    {
        return $user->checkPermissionTo('update Tallies');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tallies $tallies): bool
    {
        return $user->checkPermissionTo('delete Tallies');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Tallies');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tallies $tallies): bool
    {
        return $user->checkPermissionTo('restore Tallies');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Tallies');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Tallies $tallies): bool
    {
        return $user->checkPermissionTo('replicate Tallies');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Tallies');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tallies $tallies): bool
    {
        return $user->checkPermissionTo('force-delete Tallies');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Tallies');
    }
}
