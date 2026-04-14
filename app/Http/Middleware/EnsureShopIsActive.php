<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureShopIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            /** @var User $user */
            $user = auth()->user();

            if (! $user->is_active) {
                auth()->logout();
                abort(403, 'User account is inactive.');
            }

            if (! $user->isSuperAdmin() && (! $user->shop || ! $user->shop->is_active)) {
                auth()->logout();
                abort(403, 'Shop is inactive.');
            }
        }

        return $next($request);
    }
}
