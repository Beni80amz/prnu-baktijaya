<?php

namespace App\Policies;

use App\Models\Slider;
use App\Models\User;
use App\Traits\HandlesFilamentAccess;
use Illuminate\Auth\Access\Response;

class SliderPolicy
{
    use HandlesFilamentAccess;

    public function viewAny(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten']);
    }

    public function view(User $user, Slider $slider): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten']);
    }

    public function create(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten']);
    }

    public function update(User $user, Slider $slider): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten']);
    }

    public function delete(User $user, Slider $slider): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten']);
    }

    public function restore(User $user, Slider $slider): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function forceDelete(User $user, Slider $slider): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }
}
