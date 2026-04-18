<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Challan;
use App\Models\Bill;
use App\Models\Customer;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        $user = auth()->user();

        if (!$user || $user->role === 'staff') {
            return [];
        }

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Monthly Meters with trend
        $monthlyMeters = Challan::whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('total_meters');

        $lastMonthMeters = Challan::whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total_meters');

        $metersTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $metersTrend[] = (float) Challan::whereDate('date', $now->copy()->subDays($i)->toDateString())
                ->sum('total_meters');
        }

        // Monthly Revenue with trend
        $monthlyRevenue = Bill::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('final_total');

        $lastMonthRevenue = Bill::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('final_total');

        $revenueTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $revenueTrend[] = (float) Bill::whereDate('created_at', $now->copy()->subDays($i)->toDateString())
                ->sum('final_total');
        }

        // Today's revenue
        $todayRevenue = Bill::whereDate('created_at', $now->toDateString())->sum('final_total');

        // Pending bills
        $pendingBillsCount = Challan::doesntHave('bill')->count();

        // GST Payable
        $gstPayable = Bill::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->selectRaw('SUM(cgst_amount) + SUM(sgst_amount) as total_gst')
            ->value('total_gst') ?? 0;

        // Total Customers & monthly challans
        $totalCustomers = Customer::count();
        $monthlyChallans = Challan::whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->count();

        $metersChange = $lastMonthMeters > 0
            ? round((($monthlyMeters - $lastMonthMeters) / $lastMonthMeters) * 100, 1)
            : 0;

        $revenueChange = $lastMonthRevenue > 0
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        return [
            Stat::make('Monthly Meters', number_format($monthlyMeters, 2) . ' m')
                ->description(($metersChange >= 0 ? '+' : '') . $metersChange . '% vs last month')
                ->descriptionIcon($metersChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart($metersTrend)
                ->color($metersChange >= 0 ? 'success' : 'danger'),

            Stat::make('Monthly Revenue', '₹ ' . number_format($monthlyRevenue, 2))
                ->description(($revenueChange >= 0 ? '+' : '') . $revenueChange . '% vs last month')
                ->descriptionIcon($revenueChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart($revenueTrend)
                ->color($revenueChange >= 0 ? 'success' : 'danger'),

            Stat::make("Today's Revenue", '₹ ' . number_format($todayRevenue, 2))
                ->description('Bills generated today')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Pending Bills', $pendingBillsCount . ' challans')
                ->description('Challans awaiting billing')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingBillsCount > 0 ? 'warning' : 'success'),

            Stat::make('GST Payable (Monthly)', '₹ ' . number_format($gstPayable, 2))
                ->description('CGST + SGST this month')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('danger'),

            Stat::make('Total Customers', $totalCustomers)
                ->description($monthlyChallans . ' challans this month')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
        ];
    }
}
