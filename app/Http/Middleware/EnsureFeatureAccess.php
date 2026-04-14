<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureAccess
{
    public function __construct(private readonly SubscriptionService $subscriptionService)
    {
    }

    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (auth()->check() && auth()->user()->shop) {
            $enabled = $this->subscriptionService->isFeatureEnabled(auth()->user()->shop, $feature);

            abort_if(! $enabled, 403, "Feature [{$feature}] is not available on the current plan.");
        }

        return $next($request);
    }
}
