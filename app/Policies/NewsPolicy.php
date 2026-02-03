<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NewsPolicy
{
    private function denyResponse(): Response
    {
        return Response::deny('Anda Tidak Berhak untuk mengakses MENU ini!');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasAnyRole(['super_admin', 'admin_konten', 'kontributor'])
            ? Response::allow()
            : $this->denyResponse();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, News $news): Response
    {
        if ($user->hasRole('kontributor')) {
            return $news->user_id === $user->id
                ? Response::allow()
                : $this->denyResponse();
        }

        return $user->hasAnyRole(['super_admin', 'admin_konten'])
            ? Response::allow()
            : $this->denyResponse();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasAnyRole(['super_admin', 'admin_konten', 'kontributor'])
            ? Response::allow()
            : $this->denyResponse();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, News $news): Response
    {
        if ($user->hasRole('kontributor')) {
            return $news->user_id === $user->id
                ? Response::allow()
                : $this->denyResponse();
        }

        return $user->hasAnyRole(['super_admin', 'admin_konten'])
            ? Response::allow()
            : $this->denyResponse();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, News $news): Response
    {
        return $user->hasAnyRole(['super_admin', 'admin_konten'])
            ? Response::allow()
            : $this->denyResponse();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, News $news): Response
    {
        return $user->hasRole('super_admin')
            ? Response::allow()
            : $this->denyResponse();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, News $news): Response
    {
        return $user->hasRole('super_admin')
            ? Response::allow()
            : $this->denyResponse();
    }
}
