<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Pallets;
use App\Models\User;

class PalletsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Pallets');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pallets $pallets): bool
    {
        return $user->checkPermissionTo('view Pallets');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Pallets');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pallets $pallets): bool
    {
        return $user->checkPermissionTo('update Pallets');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pallets $pallets): bool
    {
        return $user->checkPermissionTo('delete Pallets');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Pallets');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pallets $pallets): bool
    {
        return $user->checkPermissionTo('restore Pallets');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Pallets');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Pallets $pallets): bool
    {
        return $user->checkPermissionTo('replicate Pallets');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Pallets');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pallets $pallets): bool
    {
        return $user->checkPermissionTo('force-delete Pallets');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Pallets');
    }
}
