<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        app()->forgetInstance('tenant.shop_id');

        if (auth()->check()) {
            /** @var User $user */
            $user = auth()->user();

            if (! $user->isSuperAdmin() && $user->shop_id) {
                app()->instance('tenant.shop_id', $user->shop_id);
            }

            view()->share('currentUser', $user);
            view()->share('currentShop', $user->shop);
        }

        return $next($request);
    }
}
