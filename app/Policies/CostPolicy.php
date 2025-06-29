<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Cost;
use App\Models\User;

class CostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Cost');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cost $cost): bool
    {
        return $user->checkPermissionTo('view Cost');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Cost');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cost $cost): bool
    {
        return $user->checkPermissionTo('update Cost');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cost $cost): bool
    {
        return $user->checkPermissionTo('delete Cost');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Cost');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cost $cost): bool
    {
        return $user->checkPermissionTo('restore Cost');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Cost');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Cost $cost): bool
    {
        return $user->checkPermissionTo('replicate Cost');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Cost');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cost $cost): bool
    {
        return $user->checkPermissionTo('force-delete Cost');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Cost');
    }
}
