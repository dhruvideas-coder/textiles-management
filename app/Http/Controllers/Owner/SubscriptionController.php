<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(private readonly SubscriptionService $subscriptionService)
    {
    }

    public function show(): View
    {
        $shop = auth()->user()->shop;

        return view('owner.subscriptions.show', [
            'subscription' => $this->subscriptionService->activeSubscription($shop),
            'billUsage' => $this->subscriptionService->currentBillUsage($shop),
            'billLimit' => $this->subscriptionService->billLimit($shop),
        ]);
    }
}
