<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PembayaranLog;
use App\Models\User;

class PembayaranLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PembayaranLog');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PembayaranLog $pembayaranlog): bool
    {
        return $user->checkPermissionTo('view PembayaranLog');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PembayaranLog');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PembayaranLog $pembayaranlog): bool
    {
        return $user->checkPermissionTo('update PembayaranLog');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PembayaranLog $pembayaranlog): bool
    {
        return $user->checkPermissionTo('delete PembayaranLog');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any PembayaranLog');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PembayaranLog $pembayaranlog): bool
    {
        return $user->checkPermissionTo('restore PembayaranLog');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any PembayaranLog');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, PembayaranLog $pembayaranlog): bool
    {
        return $user->checkPermissionTo('replicate PembayaranLog');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder PembayaranLog');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PembayaranLog $pembayaranlog): bool
    {
        return $user->checkPermissionTo('force-delete PembayaranLog');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any PembayaranLog');
    }
}
