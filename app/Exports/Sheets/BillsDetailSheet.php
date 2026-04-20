<?php

namespace App\Exports\Sheets;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BillsDetailSheet implements FromArray, WithTitle, WithColumnWidths, WithEvents
{
    public function __construct(
        private int $month,
        private int $year,
        private Collection $bills
    ) {}

    public function title(): string
    {
        return 'Bills Detail';
    }

    public function array(): array
    {
        $period = Carbon::create($this->year, $this->month)->format('F Y');

        $rows = [
            ['GURUDEV TEXTILES ERP — Monthly Bills Detail: ' . $period, '', '', '', '', '', '', '', '', '', '', ''],
            ['Sr.', 'Bill No.', 'Bill Date', 'Customer', 'GSTIN', 'Product', 'Challan No.', 'Challan Date', 'Meters', 'Rate (₹)', 'Amount (₹)', 'CGST (₹)', 'SGST (₹)', 'Total (₹)'],
        ];

        foreach ($this->bills as $i => $bill) {
            $challan  = $bill->challan;
            $customer = $challan?->customer;
            $product  = $challan?->product;

            $rows[] = [
                $i + 1,
                $bill->bill_number,
                $bill->created_at?->format('d/m/Y') ?? '—',
                $customer?->name ?? '—',
                $customer?->gstin ?? '—',
                $product?->name ?? '—',
                $challan?->challan_number ?? '—',
                $challan?->date ? Carbon::parse($challan->date)->format('d/m/Y') : '—',
                number_format((float) $bill->total_meters, 2),
                number_format((float) $bill->rate, 2),
                number_format((float) $bill->amount, 2),
                number_format((float) $bill->cgst_amount, 2),
                number_format((float) $bill->sgst_amount, 2),
                number_format((float) $bill->final_total, 2),
            ];
        }

        // Totals row
        $rows[] = [
            'TOTAL', '', '', '', '', '', '', '',
            number_format($this->bills->sum('total_meters'), 2),
            '',
            number_format($this->bills->sum('amount'), 2),
            number_format($this->bills->sum('cgst_amount'), 2),
            number_format($this->bills->sum('sgst_amount'), 2),
            number_format($this->bills->sum('final_total'), 2),
        ];

        return $rows;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 14,
            'C' => 13,
            'D' => 28,
            'E' => 22,
            'F' => 22,
            'G' => 14,
            'H' => 14,
            'I' => 12,
            'J' => 12,
            'K' => 14,
            'L' => 13,
            'M' => 13,
            'N' => 15,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Row 1 — Title spanning all columns
                $sheet->mergeCells('A1:N1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FF1E3A8A']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEFF6FF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(28);

                // Row 2 — Header
                $sheet->getStyle('A2:N2')->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 10],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(20);

                // Data rows — alternating zebra stripes
                for ($r = 3; $r <= $lastRow - 1; $r++) {
                    $bg = ($r % 2 === 0) ? 'FFEFF6FF' : 'FFFFFFFF';
                    $sheet->getStyle("A{$r}:N{$r}")->applyFromArray([
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bg]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);
                    // Center numeric columns
                    $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("C{$r}:C{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("H{$r}:N{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }

                // Totals row — bold blue background
                $sheet->getStyle("A{$lastRow}:N{$lastRow}")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['argb' => 'FF1E3A8A']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFDBEAFE']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);
                $sheet->getStyle("A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Borders for the full table
                $sheet->getStyle("A2:N{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE2E8F0']],
                        'outline'    => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF1E3A8A']],
                    ],
                ]);

                // Freeze top 2 rows (header)
                $sheet->freezePane('A3');
            },
        ];
    }
}
