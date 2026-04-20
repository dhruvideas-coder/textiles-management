<?php

namespace App\Exports\Sheets;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class BillsSummarySheet implements FromArray, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    private int $customerBreakdownStartRow = 17;

    public function __construct(
        private int $month,
        private int $year,
        private Collection $bills
    ) {}

    public function title(): string
    {
        return 'Summary';
    }

    public function array(): array
    {
        $period      = Carbon::create($this->year, $this->month)->format('F Y');
        $totalBills  = $this->bills->count();
        $totalMeters = $this->bills->sum('total_meters');
        $totalAmount = $this->bills->sum('amount');
        $totalCgst   = $this->bills->sum('cgst_amount');
        $totalSgst   = $this->bills->sum('sgst_amount');
        $grandTotal  = $this->bills->sum('final_total');

        $rows = [
            /* 1  */ ['GURUDEV TEXTILES ERP', '', ''],
            /* 2  */ ['Monthly Bill Report — ' . $period, '', ''],
            /* 3  */ ['Generated on: ' . now()->format('d M Y, h:i A'), '', ''],
            /* 4  */ ['', '', ''],
            /* 5  */ ['REPORT SUMMARY', '', ''],
            /* 6  */ ['', '', ''],
            /* 7  */ ['Metric', 'Value', ''],
            /* 8  */ ['Total Bills', $totalBills, ''],
            /* 9  */ ['Total Meters', number_format($totalMeters, 2) . ' m', ''],
            /* 10 */ ['Total Amount (excl. GST)', '₹ ' . number_format($totalAmount, 2), ''],
            /* 11 */ ['Total CGST (2.5%)', '₹ ' . number_format($totalCgst, 2), ''],
            /* 12 */ ['Total SGST (2.5%)', '₹ ' . number_format($totalSgst, 2), ''],
            /* 13 */ ['Total GST (5%)', '₹ ' . number_format($totalCgst + $totalSgst, 2), ''],
            /* 14 */ ['Grand Total (incl. GST)', '₹ ' . number_format($grandTotal, 2), ''],
            /* 15 */ ['', '', ''],
            /* 16 */ ['CUSTOMER-WISE BREAKDOWN', '', ''],
            /* 17 */ ['Customer', 'Bills', 'Grand Total (₹)'],
        ];

        $this->customerBreakdownStartRow = count($rows);

        $grouped = $this->bills->groupBy(fn($b) => $b->challan?->customer?->name ?? 'N/A');
        foreach ($grouped as $customer => $customerBills) {
            $rows[] = [
                $customer,
                $customerBills->count(),
                number_format($customerBills->sum('final_total'), 2),
            ];
        }

        // Totals row
        $rows[] = ['Total', $totalBills, number_format($grandTotal, 2)];

        return $rows;
    }

    public function columnWidths(): array
    {
        return ['A' => 45, 'B' => 20, 'C' => 22];
    }

    public function styles(Worksheet $sheet): void {}

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Row 1 — Company Title
                $sheet->mergeCells('A1:C1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 18, 'color' => ['argb' => 'FF1E3A8A']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(32);

                // Row 2 — Report title
                $sheet->mergeCells('A2:C2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FF334155']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Row 3 — Generated date
                $sheet->mergeCells('A3:C3');
                $sheet->getStyle('A3')->applyFromArray([
                    'font'      => ['italic' => true, 'size' => 10, 'color' => ['argb' => 'FF64748B']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Row 5 — Section title REPORT SUMMARY
                $sheet->mergeCells('A5:C5');
                $sheet->getStyle('A5')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 1],
                ]);
                $sheet->getRowDimension(5)->setRowHeight(22);

                // Row 7 — Header row for summary table
                $sheet->getStyle('A7:B7')->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF334155']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 1],
                ]);

                // Rows 8-14 — Summary data rows
                for ($r = 8; $r <= 14; $r++) {
                    $bg = ($r % 2 === 0) ? 'FFF8FAFC' : 'FFFFFFFF';
                    $sheet->getStyle("A{$r}:B{$r}")->applyFromArray([
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bg]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 1],
                    ]);
                }

                // Grand Total row (14) — bold
                $sheet->getStyle('A14:B14')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFDBEAFE']],
                ]);

                // Row 16 — CUSTOMER BREAKDOWN section title
                $sheet->mergeCells('A16:C16');
                $sheet->getStyle('A16')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 1],
                ]);
                $sheet->getRowDimension(16)->setRowHeight(22);

                // Row 17 — Customer table header
                $sheet->getStyle('A17:C17')->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF334155']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 1],
                ]);

                // Customer rows — alternating colors
                $lastRow = $sheet->getHighestRow();
                for ($r = 18; $r <= $lastRow - 1; $r++) {
                    $bg = ($r % 2 === 0) ? 'FFF8FAFC' : 'FFFFFFFF';
                    $sheet->getStyle("A{$r}:C{$r}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bg]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 1],
                    ]);
                }

                // Last row — totals
                $sheet->getStyle("A{$lastRow}:C{$lastRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFDBEAFE']],
                ]);

                // Borders around data areas
                $sheet->getStyle("A7:B14")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE2E8F0']],
                        'outline'    => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF1E3A8A']],
                    ],
                ]);
                $sheet->getStyle("A17:C{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE2E8F0']],
                        'outline'    => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF1E3A8A']],
                    ],
                ]);

                // Align B and C columns in customer table to center
                $sheet->getStyle("B17:C{$lastRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
