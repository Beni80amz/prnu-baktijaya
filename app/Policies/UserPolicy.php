<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\HandlesFilamentAccess;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesFilamentAccess;

    public function viewAny(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function view(User $user, User $model): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function create(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function update(User $user, User $model): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function delete(User $user, User $model): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function restore(User $user, User $model): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function forceDelete(User $user, User $model): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }
}
