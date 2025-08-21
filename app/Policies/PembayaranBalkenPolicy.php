<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PembayaranBalken;
use App\Models\User;

class PembayaranBalkenPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PembayaranBalken');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PembayaranBalken $pembayaranbalken): bool
    {
        return $user->checkPermissionTo('view PembayaranBalken');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PembayaranBalken');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PembayaranBalken $pembayaranbalken): bool
    {
        return $user->checkPermissionTo('update PembayaranBalken');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PembayaranBalken $pembayaranbalken): bool
    {
        return $user->checkPermissionTo('delete PembayaranBalken');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any PembayaranBalken');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PembayaranBalken $pembayaranbalken): bool
    {
        return $user->checkPermissionTo('restore PembayaranBalken');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any PembayaranBalken');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, PembayaranBalken $pembayaranbalken): bool
    {
        return $user->checkPermissionTo('replicate PembayaranBalken');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder PembayaranBalken');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PembayaranBalken $pembayaranbalken): bool
    {
        return $user->checkPermissionTo('force-delete PembayaranBalken');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any PembayaranBalken');
    }
}
