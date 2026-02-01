<?php

namespace App\Policies;

use App\Models\Munfiqun;
use App\Models\User;
use App\Traits\HandlesFilamentAccess;
use Illuminate\Auth\Access\Response;

class MunfiqunPolicy
{
    use HandlesFilamentAccess;

    public function viewAny(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara']);
    }

    public function view(User $user, Munfiqun $munfiqun): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara']);
    }

    public function create(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara']);
    }

    public function update(User $user, Munfiqun $munfiqun): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara']);
    }

    public function delete(User $user, Munfiqun $munfiqun): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara']);
    }

    public function restore(User $user, Munfiqun $munfiqun): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function forceDelete(User $user, Munfiqun $munfiqun): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }
}
