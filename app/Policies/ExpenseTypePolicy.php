<?php

namespace App\Policies;

use App\Models\ExpenseType;
use App\Models\User;
use App\Traits\HandlesFilamentAccess;
use Illuminate\Auth\Access\Response;

class ExpenseTypePolicy
{
    use HandlesFilamentAccess;

    public function viewAny(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara']);
    }

    public function view(User $user, ExpenseType $expenseType): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara']);
    }

    public function create(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara']);
    }

    public function update(User $user, ExpenseType $expenseType): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara']);
    }

    public function delete(User $user, ExpenseType $expenseType): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_bendahara']);
    }

    public function restore(User $user, ExpenseType $expenseType): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function forceDelete(User $user, ExpenseType $expenseType): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }
}
