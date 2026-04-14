<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Shop;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        return view('admin.subscriptions.index', [
            'shops' => Shop::with(['subscription.plan'])->orderBy('name')->get(),
            'plans' => Plan::where('is_active', true)->orderBy('monthly_price')->get(),
        ]);
    }

    public function update(Request $request, Shop $shop): RedirectResponse
    {
        $validated = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
            'status' => ['required', 'in:trial,active,expired,cancelled'],
            'started_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'trial_ends_at' => ['nullable', 'date', 'after_or_equal:started_at'],
        ]);

        Subscription::create([
            'shop_id' => $shop->id,
            'plan_id' => $validated['plan_id'],
            'status' => $validated['status'],
            'started_at' => $validated['started_at'],
            'ends_at' => $validated['ends_at'] ?? null,
            'trial_ends_at' => $validated['trial_ends_at'] ?? null,
            'current_period_start' => now()->startOfMonth()->toDateString(),
            'current_period_end' => now()->endOfMonth()->toDateString(),
            'gateway' => $request->input('gateway'),
        ]);

        return redirect()->route('admin.subscriptions.index')->with('status', 'Subscription updated.');
    }
}
