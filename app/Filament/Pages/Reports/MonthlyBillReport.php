<?php

namespace App\Filament\Pages\Reports;

use App\Models\Bill;
use BackedEnum;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class MonthlyBillReport extends Page
{
    protected string $view = 'filament.pages.reports.monthly-bill-report';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|\UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Monthly Bill Report';

    protected static ?string $title = 'Monthly Bill Report';

    protected static ?int $navigationSort = 1;

    public int $month;
    public int $year;

    public static function canAccess(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'owner']);
    }

    public function mount(): void
    {
        $this->month = (int) now()->month;
        $this->year  = (int) now()->year;
    }

    #[Computed]
    public function bills(): Collection
    {
        return Bill::withoutGlobalScope('owner')
            ->when(auth()->user()->role !== 'admin', function ($q) {
                $ownerId = auth()->user()->role === 'owner'
                    ? auth()->id()
                    : auth()->user()->owner_id;
                $q->where('bills.owner_id', $ownerId);
            })
            ->with(['challan.customer', 'challan.product'])
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->orderBy('bill_number')
            ->get();
    }

    #[Computed]
    public function summary(): array
    {
        $bills = $this->bills;
        return [
            'total_bills'  => $bills->count(),
            'total_meters' => (float) $bills->sum('total_meters'),
            'total_amount' => (float) $bills->sum('amount'),
            'total_cgst'   => (float) $bills->sum('cgst_amount'),
            'total_sgst'   => (float) $bills->sum('sgst_amount'),
            'grand_total'  => (float) $bills->sum('final_total'),
        ];
    }

    #[Computed]
    public function customerBreakdown(): Collection
    {
        return $this->bills
            ->groupBy(fn($b) => $b->challan?->customer?->name ?? 'N/A')
            ->map(fn($group, $name) => [
                'name'         => $name,
                'count'        => $group->count(),
                'total_meters' => (float) $group->sum('total_meters'),
                'amount'       => (float) $group->sum('amount'),
                'gst'          => (float) ($group->sum('cgst_amount') + $group->sum('sgst_amount')),
                'grand_total'  => (float) $group->sum('final_total'),
            ])
            ->sortByDesc('grand_total')
            ->values();
    }

    public function getAvailableYears(): array
    {
        $years = [];
        for ($y = now()->year; $y >= now()->year - 5; $y--) {
            $years[] = $y;
        }
        return $years;
    }

    public function getPeriodLabel(): string
    {
        return Carbon::create($this->year, $this->month)->format('F Y');
    }

    public function getExcelUrl(): string
    {
        return route('reports.monthly-bills.excel', ['month' => $this->month, 'year' => $this->year]);
    }

    public function getPdfUrl(): string
    {
        return route('reports.monthly-bills.pdf', ['month' => $this->month, 'year' => $this->year]);
    }
}
