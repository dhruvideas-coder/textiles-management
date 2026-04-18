<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Challan;
use Illuminate\Support\Facades\DB;

class ChallanStatusChart extends ChartWidget
{
    protected ?string $heading = 'Challan Status Overview';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role !== 'staff';
    }

    protected function getData(): array
    {
        $statuses = Challan::query()
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $colorMap = [
            'At Mill'       => '#f59e0b',
            'Process House' => '#3b82f6',
            'In Stock'      => '#10b981',
            'Billed'        => '#8b5cf6',
        ];

        $labels = $statuses->pluck('status')->toArray();
        $data = $statuses->pluck('count')->toArray();
        $colors = array_map(fn ($label) => $colorMap[$label] ?? '#6b7280', $labels);

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'hoverOffset' => 8,
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
