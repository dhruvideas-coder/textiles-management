<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Bill;
use Carbon\Carbon;

class DailySalesChart extends ChartWidget
{
    protected ?string $heading = 'Daily Sales (Last 7 Days)';
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role !== 'staff';
    }

    protected function getData(): array
    {
        $startDate = Carbon::today()->subDays(6);
        $endDate = Carbon::today();

        $sales = Bill::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');
            $dateString = $date->toDateString();
            $data[] = $sales[$dateString] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sales (₹)',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
