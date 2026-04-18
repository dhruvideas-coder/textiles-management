<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Bill;
use Carbon\Carbon;

class DailySalesChart extends ChartWidget
{
    protected ?string $heading = 'Daily Revenue & GST Trend';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';
    public ?string $filter = '14';

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role !== 'staff';
    }

    protected function getFilters(): ?array
    {
        return [
            '7'  => 'Last 7 Days',
            '14' => 'Last 14 Days',
            '30' => 'Last 30 Days',
        ];
    }

    protected function getData(): array
    {
        $days = (int) ($this->filter ?? 14);
        $labels = [];
        $salesData = [];
        $gstData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateString = $date->toDateString();
            $labels[] = $date->format('d M');
            $salesData[] = round((float) Bill::whereDate('created_at', $dateString)->sum('final_total'), 2);
            $gstData[] = round((float) (Bill::whereDate('created_at', $dateString)
                ->selectRaw('SUM(cgst_amount) + SUM(sgst_amount) as total_gst')
                ->value('total_gst') ?? 0), 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (₹)',
                    'data' => $salesData,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.12)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => '#3b82f6',
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ],
                [
                    'label' => 'GST (₹)',
                    'data' => $gstData,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.08)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => '#ef4444',
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
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
