<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use App\Traits\HandlesFilamentAccess;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    use HandlesFilamentAccess;

    public function viewAny(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten', 'kontributor']);
    }

    public function view(User $user, Article $article): Response
    {
        if ($user->hasRole('kontributor')) {
            return $article->user_id === $user->id
                ? Response::allow()
                : $this->denyResponse();
        }

        return $this->allowRoles($user, ['super_admin', 'admin_konten']);
    }

    public function create(User $user): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten', 'kontributor']);
    }

    public function update(User $user, Article $article): Response
    {
        if ($user->hasRole('kontributor')) {
            return $article->user_id === $user->id
                ? Response::allow()
                : $this->denyResponse();
        }

        return $this->allowRoles($user, ['super_admin', 'admin_konten']);
    }

    public function delete(User $user, Article $article): Response
    {
        return $this->allowRoles($user, ['super_admin', 'admin_konten']);
    }

    public function restore(User $user, Article $article): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }

    public function forceDelete(User $user, Article $article): Response
    {
        return $this->allowRoles($user, ['super_admin']);
    }
}
