<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;
use App\Traits\HandlesFilamentAccess;
use Illuminate\Auth\Access\Response;

class SettingPolicy
{
    use HandlesFilamentAccess;

    public function viewAny(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function view(User $user, Setting $setting): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function create(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function update(User $user, Setting $setting): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function delete(User $user, Setting $setting): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function restore(User $user, Setting $setting): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function forceDelete(User $user, Setting $setting): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }
}
