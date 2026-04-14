<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Challan;
use App\Services\AnalyticsService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly AnalyticsService $analyticsService)
    {
    }

    public function __invoke(): View
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $overview = $this->analyticsService->platformOverview();

            return view('dashboard.admin', [
                'overview' => $overview,
                'latestBills' => Bill::withoutGlobalScope('shop')->latest()->limit(8)->get(),
                'latestChallans' => Challan::withoutGlobalScope('shop')->latest()->limit(8)->get(),
            ]);
        }

        return view('dashboard.owner', [
            'analytics' => $this->analyticsService->shopDashboard($user->shop),
        ]);
    }
}
