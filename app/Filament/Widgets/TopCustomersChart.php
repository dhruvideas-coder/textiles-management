<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Bill;
use Illuminate\Support\Facades\DB;

class TopCustomersChart extends ChartWidget
{
    protected ?string $heading = 'Top 5 Customers (By Revenue)';
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    protected function getData(): array
    {
        $customers = Bill::query()
            ->join('challans', 'bills.challan_id', '=', 'challans.id')
            ->join('customers', 'challans.customer_id', '=', 'customers.id')
            ->select('customers.name', DB::raw('SUM(bills.final_total) as total_revenue'))
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $customers->pluck('total_revenue')->map(fn ($v) => round((float) $v, 2))->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444',
                    ],
                    'hoverOffset' => 8,
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $customers->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
