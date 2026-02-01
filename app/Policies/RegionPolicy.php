<?php

namespace App\Policies;

use App\Models\Region;
use App\Models\User;
use App\Traits\HandlesFilamentAccess;
use Illuminate\Auth\Access\Response;

class RegionPolicy
{
    use HandlesFilamentAccess;

    public function viewAny(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara', 'admin_layanan']);
    }

    public function view(User $user, Region $region): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara', 'admin_layanan']);
    }

    public function create(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara', 'admin_layanan']);
    }

    public function update(User $user, Region $region): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara', 'admin_layanan']);
    }

    public function delete(User $user, Region $region): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara', 'admin_layanan']);
    }

    public function restore(User $user, Region $region): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function forceDelete(User $user, Region $region): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }
}
