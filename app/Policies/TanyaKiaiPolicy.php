<?php

namespace App\Policies;

use App\Models\TanyaKiai;
use App\Models\User;
use App\Traits\HandlesFilamentAccess;
use Illuminate\Auth\Access\Response;

class TanyaKiaiPolicy
{
    use HandlesFilamentAccess;

    public function viewAny(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_layanan']);
    }

    public function view(User $user, TanyaKiai $tanyaKiai): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_layanan']);
    }

    public function create(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_layanan']);
    }

    public function update(User $user, TanyaKiai $tanyaKiai): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_layanan']);
    }

    public function delete(User $user, TanyaKiai $tanyaKiai): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_layanan']);
    }

    public function restore(User $user, TanyaKiai $tanyaKiai): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function forceDelete(User $user, TanyaKiai $tanyaKiai): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }
}
