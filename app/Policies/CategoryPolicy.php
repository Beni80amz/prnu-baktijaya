<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use App\Traits\HandlesFilamentAccess;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    use HandlesFilamentAccess;

    public function viewAny(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara', 'admin_konten']);
    }

    public function view(User $user, Category $category): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara', 'admin_konten']);
    }

    public function create(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara', 'admin_konten']);
    }

    public function update(User $user, Category $category): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara', 'admin_konten']);
    }

    public function delete(User $user, Category $category): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara', 'admin_konten']);
    }

    public function restore(User $user, Category $category): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function forceDelete(User $user, Category $category): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }
}
