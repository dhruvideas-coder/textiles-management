<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Bill;
use Carbon\Carbon;

class MonthlyRevenueChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Revenue & GST (Last 6 Months)';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 2;

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role !== 'staff';
    }

    protected function getData(): array
    {
        $labels = [];
        $revenues = [];
        $gstData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');

            $revenues[] = round((float) Bill::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount'), 2);

            $gstData[] = round((float) (Bill::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->selectRaw('SUM(cgst_amount) + SUM(sgst_amount) as total_gst')
                ->value('total_gst') ?? 0), 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (₹)',
                    'data' => $revenues,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.85)',
                    'borderColor' => '#3b82f6',
                    'borderWidth' => 2,
                    'borderRadius' => 6,
                    'borderSkipped' => false,
                ],
                [
                    'label' => 'GST (₹)',
                    'data' => $gstData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.75)',
                    'borderColor' => '#ef4444',
                    'borderWidth' => 2,
                    'borderRadius' => 6,
                    'borderSkipped' => false,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
