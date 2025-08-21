<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PalletLog;
use App\Models\User;

class PalletLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PalletLog');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PalletLog $palletlog): bool
    {
        return $user->checkPermissionTo('view PalletLog');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PalletLog');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PalletLog $palletlog): bool
    {
        return $user->checkPermissionTo('update PalletLog');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PalletLog $palletlog): bool
    {
        return $user->checkPermissionTo('delete PalletLog');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any PalletLog');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PalletLog $palletlog): bool
    {
        return $user->checkPermissionTo('restore PalletLog');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any PalletLog');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, PalletLog $palletlog): bool
    {
        return $user->checkPermissionTo('replicate PalletLog');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder PalletLog');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PalletLog $palletlog): bool
    {
        return $user->checkPermissionTo('force-delete PalletLog');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any PalletLog');
    }
}
