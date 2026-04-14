<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Shop;
use App\Models\UsageLog;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class SubscriptionService
{
    public function activeSubscription(Shop $shop)
    {
        return $shop->subscription()
            ->whereIn('status', ['trial', 'active'])
            ->where(function ($query): void {
                $query->whereNull('ends_at')->orWhereDate('ends_at', '>=', now()->toDateString());
            })
            ->first();
    }

    public function canCreateBill(Shop $shop): bool
    {
        $limit = $this->billLimit($shop);

        if ($limit === null) {
            return true;
        }

        return $this->currentBillUsage($shop) < $limit;
    }

    public function assertCanCreateBill(Shop $shop): void
    {
        if (! $this->canCreateBill($shop)) {
            throw ValidationException::withMessages([
                'plan' => 'Monthly bill creation limit reached for current subscription.',
            ]);
        }
    }

    public function assertCanAddStaff(Shop $shop): void
    {
        $subscription = $this->activeSubscription($shop);
        $limit = $subscription?->plan?->max_staff_users;

        if ($limit === null) {
            return;
        }

        $staffCount = $shop->users()->role(User::ROLE_STAFF)->count();
        if ($staffCount >= $limit) {
            throw ValidationException::withMessages([
                'plan' => 'Staff limit reached for the current plan.',
            ]);
        }
    }

    public function isFeatureEnabled(Shop $shop, string $feature): bool
    {
        $subscription = $this->activeSubscription($shop);

        if (! $subscription || ! $subscription->plan) {
            return false;
        }

        $features = $subscription->plan->features ?? [];

        if (empty($features)) {
            return true;
        }

        return (bool) ($features[$feature] ?? false);
    }

    public function incrementUsage(Shop $shop, string $metric, int $value = 1): void
    {
        $period = Carbon::now()->format('Y-m');
        $limit = $metric === 'bills' ? $this->billLimit($shop) : null;

        $usage = UsageLog::firstOrCreate(
            [
                'shop_id' => $shop->id,
                'metric' => $metric,
                'period' => $period,
            ],
            [
                'used' => 0,
                'limit_value' => $limit,
            ]
        );

        $usage->used += $value;
        $usage->limit_value = $limit;
        $usage->save();
    }

    public function currentBillUsage(Shop $shop): int
    {
        return Bill::withoutGlobalScope('shop')
            ->where('shop_id', $shop->id)
            ->whereMonth('bill_date', now()->month)
            ->whereYear('bill_date', now()->year)
            ->count();
    }

    public function billLimit(Shop $shop): ?int
    {
        $subscription = $this->activeSubscription($shop);

        return $subscription?->plan?->max_bills_per_month;
    }
}
