<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (! auth()->check()) {
            abort(403, 'Unauthorized');
        }

        /** @var User $user */
        $user = auth()->user();
        $roleList = array_map('trim', explode('|', $roles));

        if (! $user->hasAnyRole($roleList)) {
            abort(403, 'Insufficient role access.');
        }

        return $next($request);
    }
}
