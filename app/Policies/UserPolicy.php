<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function view(User $user, User $model): bool
    {
        if ($user->role === 'admin') return true;
        // Owner can view their own staff members
        return $user->role === 'owner' && ($model->owner_id === $user->id || $model->id === $user->id);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function update(User $user, User $model): bool
    {
        if ($user->role === 'admin') return true;
        return $user->role === 'owner' && $model->owner_id === $user->id;
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->role === 'admin') return true;
        return $user->role === 'owner' && $model->owner_id === $user->id;
    }

    public function deleteAny(User $user): bool
    {
        return $user->role === 'admin';
    }
}
