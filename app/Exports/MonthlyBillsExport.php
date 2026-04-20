<?php

namespace App\Exports;

use App\Exports\Sheets\BillsDetailSheet;
use App\Exports\Sheets\BillsSummarySheet;
use App\Models\Bill;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MonthlyBillsExport implements WithMultipleSheets
{
    private Collection $bills;

    public function __construct(private int $month, private int $year)
    {
        $this->bills = Bill::withoutGlobalScope('owner')
            ->when(auth()->user()->role !== 'admin', function ($q) {
                $ownerId = auth()->user()->role === 'owner'
                    ? auth()->id()
                    : auth()->user()->owner_id;
                $q->where('bills.owner_id', $ownerId);
            })
            ->with(['challan.customer', 'challan.product'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('bill_number')
            ->get();
    }

    public function sheets(): array
    {
        return [
            new BillsSummarySheet($this->month, $this->year, $this->bills),
            new BillsDetailSheet($this->month, $this->year, $this->bills),
        ];
    }
}
