<?php

namespace App\Policies;

use App\Models\Setoran;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SetoranPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['guru', 'wali_murid']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Setoran $setoran): bool
    {
        return in_array($user->role, ['guru', 'wali_murid']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'guru';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Setoran $setoran): bool
    {
        return $user->role === 'guru';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Setoran $setoran): bool
    {
        return $user->role === 'guru';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Setoran $setoran): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Setoran $setoran): bool
    {
        return false;
    }
}
