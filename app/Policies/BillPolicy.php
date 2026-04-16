<?php

namespace App\Policies;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner', 'staff']);
    }

    public function view(User $user, Bill $bill): bool
    {
        if ($user->role === 'admin') return true;
        if ($user->role === 'owner') return $bill->owner_id === $user->id;
        if ($user->role === 'staff') return $bill->owner_id === $user->owner_id;
        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function update(User $user, Bill $bill): bool
    {
        if ($user->role === 'admin') return true;
        return $user->role === 'owner' && $bill->owner_id === $user->id;
    }

    public function delete(User $user, Bill $bill): bool
    {
        if ($user->role === 'admin') return true;
        return $user->role === 'owner' && $bill->owner_id === $user->id;
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }
}
