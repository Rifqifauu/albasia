<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\KubikasiLog;
use App\Models\User;

class KubikasiLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any KubikasiLog');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KubikasiLog $kubikasilog): bool
    {
        return $user->checkPermissionTo('view KubikasiLog');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create KubikasiLog');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KubikasiLog $kubikasilog): bool
    {
        return $user->checkPermissionTo('update KubikasiLog');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KubikasiLog $kubikasilog): bool
    {
        return $user->checkPermissionTo('delete KubikasiLog');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any KubikasiLog');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KubikasiLog $kubikasilog): bool
    {
        return $user->checkPermissionTo('restore KubikasiLog');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any KubikasiLog');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, KubikasiLog $kubikasilog): bool
    {
        return $user->checkPermissionTo('replicate KubikasiLog');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder KubikasiLog');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KubikasiLog $kubikasilog): bool
    {
        return $user->checkPermissionTo('force-delete KubikasiLog');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any KubikasiLog');
    }
}
