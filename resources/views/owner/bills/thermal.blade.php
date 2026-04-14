<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $bill->bill_number }} — Thermal</title>
    <style>
        * { margin: 0; padding: 0; }
        body { width: 80mm; font-family: 'Courier New', monospace; font-size: 11px; color: #000; padding: 5mm; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 4px 0; }
        .right { text-align: right; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 1px 0; vertical-align: top; }
        .items td { border-bottom: 1px dotted #ccc; padding: 2px 0; }
        @media print { body { margin: 0; } @page { margin: 0; size: 80mm auto; } }
    </style>
</head>
<body onload="window.print()">
    <div class="center bold" style="font-size:14px;">{{ $bill->shop?->name ?? 'Textile Shop' }}</div>
    @if($bill->shop?->address)<div class="center" style="font-size:9px;">{{ $bill->shop->address }}, {{ $bill->shop->city }}</div>@endif
    @if($bill->shop?->phone)<div class="center" style="font-size:9px;">Ph: {{ $bill->shop->phone }}</div>@endif
    @if($bill->shop?->gstin)<div class="center" style="font-size:9px;">GSTIN: {{ $bill->shop->gstin }}</div>@endif
    <div class="line"></div>
    <table><tr><td class="bold">{{ $bill->bill_number }}</td><td class="right">{{ $bill->bill_date?->format('d/m/Y') }}</td></tr></table>
    @if($bill->customer)<div style="font-size:10px;">To: {{ $bill->customer->name }}</div>@endif
    <div class="line"></div>
    <table class="items">
        <tr class="bold"><td>Item</td><td class="right">Mtr</td><td class="right">Rate</td><td class="right">Amt</td></tr>
        @foreach($bill->items as $item)
            <tr><td>{{ Str::limit($item->description, 16) }}</td><td class="right">{{ $item->meters }}</td><td class="right">{{ $item->rate }}</td><td class="right">{{ number_format($item->amount, 2) }}</td></tr>
        @endforeach
    </table>
    <div class="line"></div>
    <table>
        <tr><td>Subtotal</td><td class="right">{{ number_format($bill->subtotal, 2) }}</td></tr>
        @if($bill->discount > 0)<tr><td>Discount</td><td class="right">-{{ number_format($bill->discount, 2) }}</td></tr>@endif
        @if($bill->transport_charges > 0)<tr><td>Transport</td><td class="right">{{ number_format($bill->transport_charges, 2) }}</td></tr>@endif
        @if($bill->tax_total > 0)<tr><td>Tax</td><td class="right">{{ number_format($bill->tax_total, 2) }}</td></tr>@endif
        @if($bill->round_off != 0)<tr><td>Round Off</td><td class="right">{{ number_format($bill->round_off, 2) }}</td></tr>@endif
    </table>
    <div class="line"></div>
    <table><tr class="bold" style="font-size:14px;"><td>TOTAL</td><td class="right">₹{{ number_format($bill->total, 2) }}</td></tr></table>
    <div class="line"></div>
    <div class="center" style="font-size:9px;margin-top:4px;">{{ $bill->shop?->footer_text ?? 'Thank you!' }}</div>
</body>
</html>
