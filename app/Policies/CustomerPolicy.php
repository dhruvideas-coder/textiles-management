<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function view(User $user, Customer $customer): bool
    {
        if ($user->role === 'admin') return true;
        return $user->role === 'owner' && ($customer->owner_id === $user->id || $customer->owner_id === null);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function update(User $user, Customer $customer): bool
    {
        if ($user->role === 'admin') return true;
        return $user->role === 'owner' && ($customer->owner_id === $user->id || $customer->owner_id === null);
    }

    public function delete(User $user, Customer $customer): bool
    {
        if ($user->role === 'admin') return true;
        return $user->role === 'owner' && ($customer->owner_id === $user->id || $customer->owner_id === null);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }
}
