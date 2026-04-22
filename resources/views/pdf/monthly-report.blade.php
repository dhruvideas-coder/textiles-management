<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Bill Report — {{ $period }}</title>
    @php
        $bizName    = $businessDetail?->business_name    ?? 'GURUDEV TEXTILES';
        $bizAddress = $businessDetail?->business_address ?? 'Plot No. 38, Sonal Ind. Estate-3, G.H.B. Road, Behind Chickuwadi, Near New Water Tank, SURAT.';
        $bizMobile  = $businessDetail?->mobile           ?? '98790 69490';
        $bizGstin   = $businessDetail?->gstin            ?? '24EZAPP5247K1Z3';
        $nameParts  = explode(' ', trim($bizName), 2);
        $nameFirst  = $nameParts[0];
        $nameRest   = $nameParts[1] ?? '';
    @endphp
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            /* font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; */
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9.5px;
            color: #0f172a;
            background: #fff;
            padding: 6px;
        }
        .main-wrapper {
            border: 2px solid #1e3a8a;
            width: 100%;
        }

        /* ── HEADER ── */
        .header {
            background: #f8fafc;
            border-bottom: 2px solid #1e3a8a;
            padding: 10px 16px;
            position: relative;
        }
        .header-inner { display: table; width: 100%; }
        .header-left  { display: table-cell; vertical-align: middle; width: 60%; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; width: 40%; }

        .biz-name {
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 1px;
            line-height: 1.1;
        }
        .biz-name .first  { color: #1e3a8a; }
        .biz-name .rest   { color: #0ea5e9; }

        .biz-sub {
            font-size: 8.5px;
            color: #475569;
            margin-top: 2px;
        }
        .biz-detail {
            font-size: 8.5px;
            color: #334155;
            margin-top: 3px;
            line-height: 1.5;
        }
        .report-tag {
            background: #1e3a8a;
            color: #fff;
            padding: 3px 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 3px;
            display: inline-block;
            margin-bottom: 4px;
        }
        .report-period {
            font-size: 16px;
            font-weight: 800;
            color: #1e3a8a;
        }
        .generated-on {
            font-size: 8px;
            color: #64748b;
            margin-top: 2px;
        }

        /* ── SUMMARY BAR ── */
        .summary-bar {
            display: table;
            width: 100%;
            border-bottom: 1px solid #e2e8f0;
        }
        .summary-cell {
            display: table-cell;
            text-align: center;
            padding: 8px 6px;
            border-right: 1px solid #e2e8f0;
        }
        .summary-cell:last-child { border-right: none; }
        .summary-label {
            font-size: 7.5px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .summary-value {
            font-size: 13px;
            font-weight: 800;
            color: #1e3a8a;
            margin-top: 1px;
        }
        .summary-value.green  { color: #059669; }
        .summary-value.orange { color: #d97706; }
        .summary-value.red    { color: #dc2626; }

        /* ── TABLE ── */
        .section-title {
            background: #1e3a8a;
            color: #fff;
            font-size: 9px;
            font-weight: bold;
            padding: 5px 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        table.bills {
            width: 100%;
            border-collapse: collapse;
        }
        table.bills thead tr {
            background: #334155;
            color: #fff;
        }
        table.bills thead th {
            padding: 5px 4px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 0.4px;
            border: 1px solid #1e3a8a;
        }
        table.bills tbody tr:nth-child(odd)  { background: #f8fafc; }
        table.bills tbody tr:nth-child(even) { background: #eff6ff; }
        table.bills tbody td {
            padding: 4px 4px;
            font-size: 8.5px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .td-center { text-align: center; }
        .td-right  { text-align: right; }
        .td-num    { font-family: 'Courier New', monospace; text-align: right; }

        /* Totals row */
        table.bills tfoot tr {
            background: #dbeafe;
        }
        table.bills tfoot td {
            padding: 5px 4px;
            font-size: 9px;
            font-weight: 800;
            color: #1e3a8a;
            border: 1px solid #1e3a8a;
        }

        /* ── CUSTOMER BREAKDOWN ── */
        table.breakdown {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        table.breakdown thead tr { background: #334155; color: #fff; }
        table.breakdown thead th {
            padding: 5px 8px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
        }
        table.breakdown tbody tr:nth-child(odd)  { background: #f8fafc; }
        table.breakdown tbody tr:nth-child(even) { background: #fff; }
        table.breakdown tbody td {
            padding: 4px 8px;
            font-size: 8.5px;
            border-bottom: 1px solid #e2e8f0;
        }
        table.breakdown tfoot td {
            padding: 5px 8px;
            font-size: 9px;
            font-weight: 800;
            color: #1e3a8a;
            background: #dbeafe;
        }

        /* ── FOOTER ── */
        .footer {
            border-top: 1px solid #e2e8f0;
            padding: 6px 12px;
            font-size: 7.5px;
            color: #94a3b8;
            text-align: center;
        }

        /* Two-column layout for bottom section */
        .bottom-section { display: table; width: 100%; }
        .bottom-left    { display: table-cell; width: 65%; vertical-align: top; border-right: 1px solid #e2e8f0; }
        .bottom-right   { display: table-cell; width: 35%; vertical-align: top; }
    </style>
</head>
<body>
<div class="main-wrapper">

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-inner">
            <div class="header-left">
                <div class="biz-name">
                    <span class="first">{{ $nameFirst }}</span>
                    @if($nameRest) <span class="rest">{{ $nameRest }}</span>@endif
                </div>
                <div class="biz-sub">Manufacturers &amp; Dealers in Art Silk Cloth</div>
                <div class="biz-detail">
                    {{ $bizAddress }}<br>
                    Mob: {{ $bizMobile }} &nbsp;|&nbsp; GSTIN: {{ $bizGstin }}
                </div>
            </div>
            <div class="header-right">
                <div class="report-tag">Monthly Bill Report</div>
                <div class="report-period">{{ $period }}</div>
                <div class="generated-on">Generated: {{ now()->format('d M Y, h:i A') }}</div>
            </div>
        </div>
    </div>

    {{-- ── SUMMARY BAR ── --}}
    <div class="summary-bar">
        <div class="summary-cell">
            <div class="summary-label">Total Bills</div>
            <div class="summary-value">{{ $summary['total_bills'] }}</div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Total Meters</div>
            <div class="summary-value">{{ number_format($summary['total_meters'], 2) }} m</div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Amount (excl. GST)</div>
            <div class="summary-value green">₹ {{ number_format($summary['total_amount'], 2) }}</div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">CGST (2.5%)</div>
            <div class="summary-value orange">₹ {{ number_format($summary['total_cgst'], 2) }}</div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">SGST (2.5%)</div>
            <div class="summary-value orange">₹ {{ number_format($summary['total_sgst'], 2) }}</div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Grand Total (incl. GST)</div>
            <div class="summary-value red">₹ {{ number_format($summary['grand_total'], 2) }}</div>
        </div>
    </div>

    {{-- ── BILLS TABLE ── --}}
    <div class="section-title">All Bills — {{ $period }}</div>
    <table class="bills">
        <thead>
            <tr>
                <th style="width:28px">Sr.</th>
                <th>Bill No.</th>
                <th>Bill Date</th>
                <th>Customer</th>
                <th>GSTIN</th>
                <th>Product</th>
                <th>Challan No.</th>
                <th>Challan Date</th>
                <th>Meters</th>
                <th>Rate (₹)</th>
                <th>Amount (₹)</th>
                <th>CGST (₹)</th>
                <th>SGST (₹)</th>
                <th>Total (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bills as $i => $bill)
                @php
                    $challan  = $bill->challan;
                    $customer = $challan?->customer;
                    $product  = $challan?->product;
                @endphp
                <tr>
                    <td class="td-center">{{ $i + 1 }}</td>
                    <td class="td-center" style="font-weight:600;color:#1e3a8a">{{ $bill->bill_number }}</td>
                    <td class="td-center">{{ $bill->created_at?->format('d/m/Y') ?? '—' }}</td>
                    <td>{{ $customer?->name ?? '—' }}</td>
                    <td class="td-center" style="font-size:7.5px">{{ $customer?->gstin ?? '—' }}</td>
                    <td>{{ $product?->name ?? '—' }}</td>
                    <td class="td-center">{{ $challan?->challan_number ?? '—' }}</td>
                    <td class="td-center">{{ $challan?->date ? \Carbon\Carbon::parse($challan->date)->format('d/m/Y') : '—' }}</td>
                    <td class="td-num">{{ number_format($bill->total_meters, 2) }}</td>
                    <td class="td-num">{{ number_format($bill->rate, 2) }}</td>
                    <td class="td-num">{{ number_format($bill->amount, 2) }}</td>
                    <td class="td-num">{{ number_format($bill->cgst_amount, 2) }}</td>
                    <td class="td-num">{{ number_format($bill->sgst_amount, 2) }}</td>
                    <td class="td-num" style="font-weight:700;color:#1e3a8a">{{ number_format($bill->final_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" style="text-align:right;padding-right:6px">TOTAL</td>
                <td class="td-num">{{ number_format($summary['total_meters'], 2) }}</td>
                <td></td>
                <td class="td-num">{{ number_format($summary['total_amount'], 2) }}</td>
                <td class="td-num">{{ number_format($summary['total_cgst'], 2) }}</td>
                <td class="td-num">{{ number_format($summary['total_sgst'], 2) }}</td>
                <td class="td-num">{{ number_format($summary['grand_total'], 2) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- ── BOTTOM SECTION: Customer Breakdown ── --}}
    @php
        $grouped = $bills->groupBy(fn($b) => $b->challan?->customer?->name ?? 'N/A');
    @endphp
    @if($grouped->count() > 1)
    <div class="bottom-section">
        <div class="bottom-left">
            <div class="section-title">Customer-wise Breakdown</div>
            <table class="breakdown">
                <thead>
                    <tr>
                        <th style="text-align:left">Customer</th>
                        <th style="text-align:center">Bills</th>
                        <th style="text-align:right">Meters</th>
                        <th style="text-align:right">Amount (₹)</th>
                        <th style="text-align:right">GST (₹)</th>
                        <th style="text-align:right">Total (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grouped as $custName => $custBills)
                        <tr>
                            <td>{{ $custName }}</td>
                            <td style="text-align:center">{{ $custBills->count() }}</td>
                            <td style="text-align:right">{{ number_format($custBills->sum('total_meters'), 2) }}</td>
                            <td style="text-align:right">{{ number_format($custBills->sum('amount'), 2) }}</td>
                            <td style="text-align:right">{{ number_format($custBills->sum('cgst_amount') + $custBills->sum('sgst_amount'), 2) }}</td>
                            <td style="text-align:right;font-weight:700">{{ number_format($custBills->sum('final_total'), 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>Total</td>
                        <td style="text-align:center">{{ $summary['total_bills'] }}</td>
                        <td style="text-align:right">{{ number_format($summary['total_meters'], 2) }}</td>
                        <td style="text-align:right">{{ number_format($summary['total_amount'], 2) }}</td>
                        <td style="text-align:right">{{ number_format($summary['total_cgst'] + $summary['total_sgst'], 2) }}</td>
                        <td style="text-align:right">{{ number_format($summary['grand_total'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="bottom-right" style="padding:0">
            <div class="section-title">Financial Summary</div>
            <table style="width:100%;border-collapse:collapse">
                @php $rows = [
                    ['Total Bills',          $summary['total_bills'],                          false],
                    ['Total Meters',         number_format($summary['total_meters'],2).' m',   false],
                    ['Amount (excl. GST)',   '₹ '.number_format($summary['total_amount'],2),   false],
                    ['CGST @ 2.5%',          '₹ '.number_format($summary['total_cgst'],2),     false],
                    ['SGST @ 2.5%',          '₹ '.number_format($summary['total_sgst'],2),     false],
                    ['Total GST (5%)',        '₹ '.number_format($summary['total_cgst']+$summary['total_sgst'],2), false],
                    ['Grand Total',          '₹ '.number_format($summary['grand_total'],2),    true],
                ]; @endphp
                @foreach($rows as $i => $row)
                    <tr style="background:{{ $i%2===0?'#f8fafc':'#fff' }}{{ $row[2]?';background:#dbeafe':'' }}">
                        <td style="padding:4px 8px;font-size:8.5px;border-bottom:1px solid #e2e8f0;{{ $row[2]?'font-weight:800;color:#1e3a8a':'' }}">{{ $row[0] }}</td>
                        <td style="padding:4px 8px;font-size:8.5px;text-align:right;border-bottom:1px solid #e2e8f0;{{ $row[2]?'font-weight:800;color:#1e3a8a':'' }}">{{ $row[1] }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    {{-- ── FOOTER ── --}}
    <div class="footer">
        This is a computer-generated report. &nbsp;|&nbsp; {{ $bizName }} &nbsp;|&nbsp; GSTIN: {{ $bizGstin }} &nbsp;|&nbsp; {{ now()->format('d M Y') }}
    </div>

</div>
</body>
</html>
