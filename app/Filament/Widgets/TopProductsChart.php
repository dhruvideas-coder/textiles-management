<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Challan;
use Illuminate\Support\Facades\DB;

class TopProductsChart extends ChartWidget
{
    protected ?string $heading = 'Top Products (By Meters)';
    protected static ?int $sort = 6;
    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role !== 'staff';
    }

    protected function getData(): array
    {
        $products = Challan::query()
            ->join('products', 'challans.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(challans.total_meters) as total'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $products->pluck('total')->map(fn ($v) => round((float) $v, 2))->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4',
                    ],
                    'hoverOffset' => 8,
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $products->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
