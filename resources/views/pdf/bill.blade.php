<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill - {{ $bill->bill_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; background: #fff; padding: 20px; }
        .header { text-align: center; padding-bottom: 10px; border-bottom: 2px solid #1e3a8a; }
        .header h1 { font-size: 26px; font-weight: bold; text-transform: uppercase; letter-spacing: 3px; color: #1e3a8a; }
        .header .subtitle { font-size: 10px; font-weight: bold; color: #1e40af; margin-top: 2px; }
        .header .contact { font-size: 10px; color: #555; margin-top: 3px; }
        .info-row { display: table; width: 100%; margin: 14px 0; border-bottom: 2px solid #1e3a8a; padding-bottom: 14px; }
        .info-left { display: table-cell; width: 55%; vertical-align: top; }
        .info-right { display: table-cell; width: 45%; vertical-align: top; text-align: right; }
        .info-left p, .info-right p { margin: 2px 0; }
        .billed-name { font-size: 15px; font-weight: bold; color: #1e3a8a; margin-top: 4px; }
        .inv-no { color: #dc2626; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { border: 1px solid #1e3a8a; padding: 5px 8px; }
        thead tr { background-color: #1e3a8a; color: #fff; text-align: center; }
        thead th { text-align: center; }
        .desc-col { text-align: left; }
        .items-body td { text-align: center; }
        .items-body .desc-col { text-align: left; font-weight: bold; }
        .items-body .tall { height: 80px; vertical-align: top; }
        .summary-table { width: 100%; border-collapse: collapse; border-left: 1px solid #1e3a8a; border-right: 1px solid #1e3a8a; border-bottom: 1px solid #1e3a8a; }
        .summary-table td { padding: 5px 8px; }
        .notes-cell { width: 60%; border-right: 1px solid #1e3a8a; vertical-align: top; font-size: 10px; font-style: italic; }
        .label-cell { text-align: right; font-weight: bold; border-bottom: 1px solid #1e3a8a; border-right: 1px solid #1e3a8a; }
        .value-cell { text-align: right; border-bottom: 1px solid #1e3a8a; }
        .grand-total-label { text-align: right; font-weight: bold; font-size: 14px; color: #1e3a8a; border-right: 1px solid #1e3a8a; background: #dbeafe; }
        .grand-total-value { text-align: right; font-weight: bold; font-size: 14px; color: #1e3a8a; background: #dbeafe; }
        .footer { display: table; width: 100%; margin-top: 30px; padding: 0 10px; }
        .footer-left { display: table-cell; vertical-align: bottom; font-size: 10px; width: 65%; }
        .footer-right { display: table-cell; text-align: center; font-weight: bold; vertical-align: bottom; }
        .sig-space { height: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Gurudev Textiles</h1>
        <p class="subtitle">TAX INVOICE</p>
        <p class="contact">Surat, Gujarat | Ph: +91 99999 99999 | GSTIN: 24AAAAA0000A1Z5</p>
    </div>

    <div class="info-row">
        <div class="info-left">
            <p><strong>Billed To:</strong></p>
            <p class="billed-name">{{ $bill->challan?->customer?->name }}</p>
            <p>{{ $bill->challan?->customer?->address }}</p>
            <p><strong>GSTIN:</strong> {{ $bill->challan?->customer?->GSTIN }}</p>
        </div>
        <div class="info-right">
            <p><strong>Invoice No:</strong> <span class="inv-no">{{ $bill->bill_number }}</span></p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($bill->created_at)->format('d/m/Y') }}</p>
            <p><strong>Challan Ref:</strong> {{ $bill->challan?->challan_number }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%;">S.No</th>
                <th class="desc-col" style="width:40%;">Description of Goods</th>
                <th style="width:18%;">Total Meters</th>
                <th style="width:18%;">Rate (Rs.)</th>
                <th style="width:19%;">Amount (Rs.)</th>
            </tr>
        </thead>
        <tbody class="items-body">
            <tr>
                <td class="tall">1</td>
                <td class="tall desc-col">{{ $bill->challan?->product?->name }}</td>
                <td class="tall">{{ number_format($bill->total_meters, 2) }}</td>
                <td class="tall">{{ number_format($bill->rate, 2) }}</td>
                <td class="tall">{{ number_format($bill->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="summary-table">
        <tr>
            <td class="notes-cell" rowspan="4">
                * No Dyeing Guarantee *<br/>
                1. Goods once sold will not be taken back.<br/>
                2. Interest @ 18% p.a. will be charged if payment is not made within stipulated time.
            </td>
            <td class="label-cell">Taxable Value</td>
            <td class="value-cell">{{ number_format($bill->amount, 2) }}</td>
        </tr>
        <tr>
            <td class="label-cell">CGST (2.5%)</td>
            <td class="value-cell">{{ number_format($bill->cgst_amount, 2) }}</td>
        </tr>
        <tr>
            <td class="label-cell">SGST (2.5%)</td>
            <td class="value-cell">{{ number_format($bill->sgst_amount, 2) }}</td>
        </tr>
        <tr>
            <td class="grand-total-label">Grand Total</td>
            <td class="grand-total-value">Rs. {{ number_format($bill->final_total, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        <div class="footer-left">
            <strong>Amount in Words:</strong><br>
            {{ (new \NumberFormatter("en_IN", \NumberFormatter::SPELLOUT))->format($bill->final_total) }} rupees only
        </div>
        <div class="footer-right">
            <p>For Gurudev Textiles</p>
            <div class="sig-space"></div>
            <p>Authorised Signatory</p>
        </div>
    </div>
</body>
</html>
