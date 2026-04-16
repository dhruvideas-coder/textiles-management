<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function view(User $user, Product $product): bool
    {
        if ($user->role === 'admin') return true;
        return $user->role === 'owner' && ($product->owner_id === $user->id || $product->owner_id === null);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function update(User $user, Product $product): bool
    {
        if ($user->role === 'admin') return true;
        return $user->role === 'owner' && ($product->owner_id === $user->id || $product->owner_id === null);
    }

    public function delete(User $user, Product $product): bool
    {
        if ($user->role === 'admin') return true;
        return $user->role === 'owner' && ($product->owner_id === $user->id || $product->owner_id === null);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }
}
