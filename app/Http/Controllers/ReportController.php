<?php

namespace App\Http\Controllers;

use App\Exports\MonthlyBillsExport;
use App\Models\Bill;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function monthlyBillsExcel(Request $request)
    {
        $month    = (int) $request->get('month', now()->month);
        $year     = (int) $request->get('year', now()->year);
        $filename = 'monthly-bills-' . Carbon::create($year, $month)->format('M-Y') . '.xlsx';

        return Excel::download(new MonthlyBillsExport($month, $year), $filename);
    }

    public function monthlyBillsPdf(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year  = (int) $request->get('year', now()->year);

        $bills = Bill::withoutGlobalScope('owner')
            ->when(auth()->user()->role !== 'admin', function ($q) {
                $ownerId = auth()->user()->role === 'owner'
                    ? auth()->id()
                    : auth()->user()->owner_id;
                $q->where('bills.owner_id', $ownerId);
            })
            ->with(['challan.customer', 'challan.product', 'challan.businessDetail', 'owner'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('bill_number')
            ->get();

        $period     = Carbon::create($year, $month)->format('F Y');
        $summary    = [
            'total_bills'   => $bills->count(),
            'total_meters'  => $bills->sum('total_meters'),
            'total_amount'  => $bills->sum('amount'),
            'total_cgst'    => $bills->sum('cgst_amount'),
            'total_sgst'    => $bills->sum('sgst_amount'),
            'grand_total'   => $bills->sum('final_total'),
        ];
        $businessDetail = auth()->user()->businessDetails()->first();
        $filename = 'monthly-bills-' . Carbon::create($year, $month)->format('M-Y') . '.pdf';

        return Pdf::loadView('pdf.monthly-report', compact('bills', 'period', 'summary', 'businessDetail'))
            ->setPaper('A4', 'landscape')
            ->download($filename);
    }
}
