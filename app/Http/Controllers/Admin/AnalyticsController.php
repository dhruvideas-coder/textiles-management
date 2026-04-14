<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Services\AnalyticsService;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __construct(private readonly AnalyticsService $analyticsService)
    {
    }

    public function index(): View
    {
        return view('admin.analytics.index', [
            'overview' => $this->analyticsService->platformOverview(),
            'bills' => Bill::withoutGlobalScope('shop')->with('shop')->latest()->paginate(20),
        ]);
    }
}
