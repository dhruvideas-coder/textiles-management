<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __construct(private readonly AnalyticsService $analyticsService)
    {
    }

    public function index(): View
    {
        return view('owner.analytics.index', [
            'stats' => $this->analyticsService->shopDashboard(auth()->user()->shop),
        ]);
    }
}
