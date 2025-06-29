<?php
namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function view(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function update(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function delete(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }
}
