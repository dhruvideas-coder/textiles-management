<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $bill->bill_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.4; }
        .container { max-width: 700px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #0f766e; padding-bottom: 12px; margin-bottom: 15px; }
        .shop-name { font-size: 20px; font-weight: bold; color: #0f766e; }
        .shop-details { font-size: 9px; color: #64748b; margin-top: 3px; }
        .bill-meta { text-align: right; }
        .bill-number { font-size: 16px; font-weight: bold; }
        .customer-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; margin-bottom: 15px; }
        .customer-box .label { font-size: 9px; text-transform: uppercase; color: #94a3b8; font-weight: bold; letter-spacing: 0.5px; }
        .customer-box .name { font-size: 13px; font-weight: bold; margin-top: 2px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.items th { background: #f1f5f9; border: 1px solid #e2e8f0; padding: 6px 8px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700; }
        table.items td { border: 1px solid #e2e8f0; padding: 6px 8px; font-size: 10px; }
        table.items td.right, table.items th.right { text-align: right; }
        .totals { float: right; width: 280px; }
        .totals table { width: 100%; border-collapse: collapse; }
        .totals td { padding: 4px 8px; font-size: 10px; }
        .totals .label { color: #64748b; }
        .totals .value { text-align: right; font-weight: 600; }
        .totals .grand { font-size: 14px; font-weight: bold; color: #0f766e; border-top: 2px solid #0f766e; }
        .footer { clear: both; margin-top: 40px; padding-top: 12px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; font-size: 9px; color: #94a3b8; }
        .signature { text-align: right; margin-top: 40px; }
        .signature .line { border-top: 1px solid #1e293b; width: 150px; display: inline-block; margin-bottom: 3px; }
    </style>
</head>
<body>
<div class="container">
    {{-- Header --}}
    <table style="width:100%;margin-bottom:15px;border-bottom:2px solid #0f766e;padding-bottom:12px;">
        <tr>
            <td style="vertical-align:top">
                @if($bill->shop?->logo_path)
                    <img src="{{ public_path('storage/' . $bill->shop->logo_path) }}" style="height:40px;margin-bottom:5px;" alt="Logo">
                @endif
                <div class="shop-name">{{ $bill->shop?->name ?? 'Textile Shop' }}</div>
                <div class="shop-details">
                    @if($bill->shop?->address){{ $bill->shop->address }}, @endif
                    {{ $bill->shop?->city }} {{ $bill->shop?->state }} {{ $bill->shop?->pincode }}<br>
                    @if($bill->shop?->phone)Ph: {{ $bill->shop->phone }} | @endif
                    @if($bill->shop?->gstin)GSTIN: {{ $bill->shop->gstin }}@endif
                </div>
            </td>
            <td style="text-align:right;vertical-align:top">
                <div class="bill-number">{{ $bill->bill_number }}</div>
                <div style="font-size:10px;color:#64748b;margin-top:3px;">
                    Date: {{ $bill->bill_date?->format('d/m/Y') }}<br>
                    @if($bill->due_date)Due: {{ $bill->due_date->format('d/m/Y') }}@endif
                </div>
            </td>
        </tr>
    </table>

    {{-- Customer --}}
    @if($bill->customer)
    <div class="customer-box">
        <div class="label">Bill To</div>
        <div class="name">{{ $bill->customer->name }}</div>
        <div style="font-size:10px;color:#64748b;">
            @if($bill->customer->address){{ $bill->customer->address }}, {{ $bill->customer->city }}<br>@endif
            @if($bill->customer->phone)Ph: {{ $bill->customer->phone }} @endif
            @if($bill->customer->gstin)| GSTIN: {{ $bill->customer->gstin }}@endif
        </div>
    </div>
    @endif

    {{-- Items --}}
    <table class="items">
        <thead><tr>
            <th style="width:30px">#</th><th>Description</th><th class="right" style="width:50px">Pcs</th><th class="right" style="width:60px">Meters</th><th class="right" style="width:60px">Rate</th><th class="right" style="width:80px">Amount</th>
        </tr></thead>
        <tbody>
            @foreach($bill->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="right">{{ $item->pieces }}</td>
                    <td class="right">{{ number_format($item->meters, 2) }}</td>
                    <td class="right">{{ number_format($item->rate, 2) }}</td>
                    <td class="right" style="font-weight:600">{{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals">
        <table>
            <tr><td class="label">Subtotal</td><td class="value">₹{{ number_format($bill->subtotal, 2) }}</td></tr>
            @if($bill->discount > 0)<tr><td class="label">Discount</td><td class="value" style="color:#dc2626">-₹{{ number_format($bill->discount, 2) }}</td></tr>@endif
            @if($bill->transport_charges > 0)<tr><td class="label">Transport</td><td class="value">₹{{ number_format($bill->transport_charges, 2) }}</td></tr>@endif
            @if($bill->cgst > 0)<tr><td class="label">CGST</td><td class="value">₹{{ number_format($bill->cgst, 2) }}</td></tr>@endif
            @if($bill->sgst > 0)<tr><td class="label">SGST</td><td class="value">₹{{ number_format($bill->sgst, 2) }}</td></tr>@endif
            @if($bill->igst > 0)<tr><td class="label">IGST</td><td class="value">₹{{ number_format($bill->igst, 2) }}</td></tr>@endif
            @if($bill->round_off != 0)<tr><td class="label">Round Off</td><td class="value">₹{{ number_format($bill->round_off, 2) }}</td></tr>@endif
            <tr><td class="grand" style="padding-top:6px">Grand Total</td><td class="grand value" style="padding-top:6px">₹{{ number_format($bill->total, 2) }}</td></tr>
        </table>
    </div>

    {{-- Signature --}}
    <div class="signature">
        <div class="line"></div><br>
        <span style="font-size:10px">Authorized Signature</span>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <span>{{ $bill->shop?->footer_text ?? 'Thank you for your business!' }}</span>
        <span>Generated on {{ now()->format('d/m/Y h:i A') }}</span>
    </div>
</div>
</body>
</html>
