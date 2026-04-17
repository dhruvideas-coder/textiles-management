<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopProductsChart extends ChartWidget
{
    protected ?string $heading = 'Top Products (By Meters)';

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role !== 'staff';
    }

    protected function getData(): array
    {
        $products = \App\Models\Challan::query()
            ->join('products', 'challans.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(challans.total_meters) as total'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Meters Sold',
                    'data' => $products->pluck('total')->map(fn ($v) => round($v, 2))->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                    ],
                ],
            ],
            'labels' => $products->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
