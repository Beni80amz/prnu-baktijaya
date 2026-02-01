<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Auth\Access\Response;

trait HandlesFilamentAccess
{
    /**
     * Standard polite denial message requested by the user.
     */
    protected function denyResponse(): Response
    {
        return Response::deny('Anda Tidak Berhak untuk mengakses MENU ini!');
    }

    /**
     * Helper to check roles and return appropriate response.
     */
    protected function allowRoles(User $user, array $roles): Response
    {
        return $user->hasAnyRole($roles)
            ? Response::allow()
            : $this->denyResponse();
    }
}
