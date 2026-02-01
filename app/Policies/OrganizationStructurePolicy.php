<?php

namespace App\Policies;

use App\Models\OrganizationStructure;
use App\Models\User;
use App\Traits\HandlesFilamentAccess;
use Illuminate\Auth\Access\Response;

class OrganizationStructurePolicy
{
    use HandlesFilamentAccess;

    public function viewAny(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten']);
    }

    public function view(User $user, OrganizationStructure $organizationStructure): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten']);
    }

    public function create(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten']);
    }

    public function update(User $user, OrganizationStructure $organizationStructure): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten']);
    }

    public function delete(User $user, OrganizationStructure $organizationStructure): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten']);
    }

    public function restore(User $user, OrganizationStructure $organizationStructure): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function forceDelete(User $user, OrganizationStructure $organizationStructure): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }
}
