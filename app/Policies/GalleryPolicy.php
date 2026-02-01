<?php

namespace App\Policies;

use App\Models\Gallery;
use App\Models\User;
use App\Traits\HandlesFilamentAccess;
use Illuminate\Auth\Access\Response;

class GalleryPolicy
{
    use HandlesFilamentAccess;

    public function viewAny(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten', 'kontributor']);
    }

    public function view(User $user, Gallery $gallery): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten', 'kontributor']);
    }

    public function create(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten', 'kontributor']);
    }

    public function update(User $user, Gallery $gallery): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten', 'kontributor']);
    }

    public function delete(User $user, Gallery $gallery): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten']);
    }

    public function restore(User $user, Gallery $gallery): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function forceDelete(User $user, Gallery $gallery): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }
}
