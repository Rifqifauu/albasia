<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Payrolls;
use App\Models\User;

class PayrollsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Payrolls');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payrolls $payrolls): bool
    {
        return $user->checkPermissionTo('view Payrolls');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Payrolls');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payrolls $payrolls): bool
    {
        return $user->checkPermissionTo('update Payrolls');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payrolls $payrolls): bool
    {
        return $user->checkPermissionTo('delete Payrolls');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Payrolls');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Payrolls $payrolls): bool
    {
        return $user->checkPermissionTo('restore Payrolls');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Payrolls');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Payrolls $payrolls): bool
    {
        return $user->checkPermissionTo('replicate Payrolls');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Payrolls');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Payrolls $payrolls): bool
    {
        return $user->checkPermissionTo('force-delete Payrolls');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Payrolls');
    }
}
