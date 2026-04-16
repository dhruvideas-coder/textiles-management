<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Challan;
use App\Models\Bill;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        if (!$user || $user->role === 'staff') {
            return [];
        }

        $monthlyMeters = Challan::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('total_meters');

        $totalRevenue = Bill::sum('amount');

        $pendingBillsCount = Challan::doesntHave('bill')->count();

        $gstPayable = Bill::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('SUM(cgst_amount) + SUM(sgst_amount) as total_gst')
            ->value('total_gst') ?? 0;

        return [
            Stat::make('Total Meters (Monthly)', number_format($monthlyMeters, 2) . ' m')
                ->description('Meters delivered this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Revenue', '₹ ' . number_format($totalRevenue, 2))
                ->description('Overall billed amount')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),

            Stat::make('Pending Bills', $pendingBillsCount . ' challans')
                ->description('Challans without bills')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('GST Payable (Monthly)', '₹ ' . number_format($gstPayable, 2))
                ->description('GST to be paid this month')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('danger'),
        ];
    }
}
