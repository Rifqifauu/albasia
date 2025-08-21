<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PalletBalken;
use App\Models\User;

class PalletBalkenPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PalletBalken');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PalletBalken $palletbalken): bool
    {
        return $user->checkPermissionTo('view PalletBalken');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PalletBalken');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PalletBalken $palletbalken): bool
    {
        return $user->checkPermissionTo('update PalletBalken');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PalletBalken $palletbalken): bool
    {
        return $user->checkPermissionTo('delete PalletBalken');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any PalletBalken');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PalletBalken $palletbalken): bool
    {
        return $user->checkPermissionTo('restore PalletBalken');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any PalletBalken');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, PalletBalken $palletbalken): bool
    {
        return $user->checkPermissionTo('replicate PalletBalken');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder PalletBalken');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PalletBalken $palletbalken): bool
    {
        return $user->checkPermissionTo('force-delete PalletBalken');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any PalletBalken');
    }
}
