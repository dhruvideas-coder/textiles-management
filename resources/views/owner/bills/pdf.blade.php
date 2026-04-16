<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TAX INVOICE - {{ $bill->bill_number }}</title>
    <style>
        @page { margin: 10px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #001; }
        .container { border: 2px solid #0056b3; padding: 8px; position: relative; min-height: 98vh; }
        
        table { width: 100%; border-collapse: collapse; }
        .border-all { border: 1px solid #0056b3; }
        .border-b { border-bottom: 1px solid #0056b3; }
        .border-r { border-right: 1px solid #0056b3; }
        
        /* Header */
        .shop-name { font-size: 38px; font-weight: 900; color: #d00; text-align: center; }
        .shop-name span { color: #0056b3; }
        .shop-tagline-box { border: 2px solid #d00; padding: 5px; text-align: center; font-weight: bold; font-size: 10px; border-radius: 5px; }
        
        /* Main Tables */
        .items-table th { background: #0056b3; color: #fff; padding: 5px; font-size: 10px; }
        .items-table td { border: 1px solid #0056b3; padding: 5px; text-align: center; vertical-align: top; }
        
        .footer-table td { border: 1px solid #0056b3; padding: 6px; }
        .label { font-weight: bold; width: 100px; display: inline-block; }
        
        .total-box { background: #0056b3; color: #fff; font-size: 20px; font-weight: 900; text-align: center; padding: 5px; }
        .guarantee { color: #d00; font-weight: bold; font-size: 14px; text-align: center; padding: 5px; border-bottom: 1px solid #0056b3; }
        
        .gujarati { font-family: 'Gujarati', sans-serif; }
    </style>
</head>
<body>
<div class="container">
    {{-- Header Strip --}}
    <table style="width: 100%; border-bottom: 2px solid #0056b3; margin-bottom: 5px;">
        <tr>
            <td style="width: 30%; text-align: center; font-size: 10px;">॥ શ્રી ગણેશાય નમઃ ॥<br>॥ જય બહુચર માતાય નમઃ ॥</td>
            <td style="width: 40%; text-align: center;">
                 <div style="border: 2px solid #0056b3; background: #0056b3; color: white; padding: 3px 15px; font-weight: bold; display: inline-block; border-radius: 5px;">TAX INVOICE</div>
            </td>
            <td style="width: 30%; text-align: right; font-weight: bold;">Mo.: {{ $bill->shop?->phone }}</td>
        </tr>
    </table>

    {{-- Shop Info --}}
    <table style="width: 100%; margin-bottom: 8px;">
        <tr>
            <td style="width: 65%;">
                <div class="shop-name">{{ strtoupper(explode(' ', $bill->shop?->name)[0]) }} <span>{{ strtoupper(substr($bill->shop?->name, strlen(explode(' ', $bill->shop?->name)[0]))) }}</span></div>
                <div style="text-align: center; font-size: 9px; margin-top: 5px;">{{ $bill->shop?->address }}, {{ $bill->shop?->city }}-{{ $bill->shop?->pincode }}</div>
            </td>
            <td style="width: 35%;">
                <div class="shop-tagline-box">
                    Manufacturer & Dealers in :<br>
                    <span style="font-size: 12px; color: #0056b3;">{{ strtoupper($bill->shop?->tagline ?? 'ART SILK CLOTH') }}</span>
                </div>
            </td>
        </tr>
    </table>

    {{-- GSTIN ROW --}}
    <table class="border-all" style="margin-bottom: -1px;">
        <tr>
            <td style="width: 60%; padding: 4px 8px; font-weight: bold; color: #d00;">GSTIN No.: {{ $bill->shop?->gstin }}</td>
            <td style="width: 40%; border-left: 1px solid #0056b3; padding: 4px 8px;">HSN Code : {{ $bill->items->first()?->product?->hsn_code }}</td>
        </tr>
    </table>

    {{-- Customer & Bill No --}}
    <table class="border-all">
        <tr>
            <td style="width: 60%; padding: 4px 8px; vertical-align: top;">
                <span class="label">Name :</span> <span style="font-size: 13px; font-weight: 900;">{{ strtoupper($bill->customer?->name ?? 'WALK-IN CUSTOMER') }}</span><br>
                <div style="height: 5px;"></div>
                <span class="label">Address :</span> {{ $bill->customer?->address ?? '—' }}<br>
                <div style="height: 5px;"></div>
                <span class="label">GSTIN :</span> {{ $bill->customer?->gstin ?? '—' }}
            </td>
            <td style="width: 40%; vertical-align: top; border-left: 1px solid #0056b3;">
                <table style="width: 100%;">
                    <tr class="border-b"><td class="label" style="padding: 4px;">Bill No.</td><td style="font-weight: bold; font-size: 13px;">{{ $bill->bill_number }}</td></tr>
                    <tr class="border-b"><td class="label" style="padding: 4px;">Date</td><td>{{ $bill->bill_date?->format('d/m/Y') }}</td></tr>
                    <tr class="border-b"><td class="label" style="padding: 4px;">Challan No.</td><td>{{ $bill->challan_number ?? '—' }}</td></tr>
                    <tr><td class="label" style="padding: 4px;">Broker</td><td style="font-weight: bold;">{{ $bill->broker_name ?? '—' }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Items Table --}}
    <table class="items-table" style="margin-top: 5px; height: 350px;">
        <thead>
            <tr>
                <th style="width: 40px;">Sr. No.</th>
                <th>Product Description</th>
                <th style="width: 60px;">Total Pcs.</th>
                <th style="width: 80px;">Total Meters</th>
                <th style="width: 80px;">Rate</th>
                <th style="width: 100px;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bill->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="text-align: left; padding-left: 15px; font-weight: bold;">{{ $item->description }}</td>
                <td>{{ $item->pieces ?: '—' }}</td>
                <td>{{ number_format($item->meters, 2) }}</td>
                <td>{{ number_format($item->rate, 2) }}</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($item->amount, 2) }}</td>
            </tr>
            @endforeach
            {{-- Fill blanks --}}
            @for($i = count($bill->items); $i < 10; $i++)
            <tr><td style="height: 25px;">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
            @endfor
        </tbody>
    </table>

    {{-- Delivery Info --}}
    <table class="border-all" style="margin-top: -1px;">
        <tr>
            <td style="width: 65%; padding: 4px 8px;">
                <span class="label">Goods Delivered to :</span> {{ $bill->delivered_to ?? '—' }}<br>
                <div style="height: 4px;"></div>
                <span class="label">Due Date :</span> {{ $bill->due_date?->format('d/m/Y') ?? '—' }}
            </td>
            <td style="width: 35%; padding: 0;">
                <table style="width: 100%;">
                    <tr class="border-b"><td style="padding: 4px; font-size: 10px;">Total Amount Before Tax</td><td style="text-align: right; font-weight: bold; padding: 4px;">{{ number_format($bill->subtotal, 2) }}</td></tr>
                    <tr class="border-b"><td style="padding: 4px; font-size: 10px;">Add: CGST (2.5%)</td><td style="text-align: right; font-weight: bold; padding: 4px;">{{ number_format($bill->cgst, 2) }}</td></tr>
                    <tr class="border-b"><td style="padding: 4px; font-size: 10px;">Add: SGST (2.5%)</td><td style="text-align: right; font-weight: bold; padding: 4px;">{{ number_format($bill->sgst, 2) }}</td></tr>
                    <tr class="border-b"><td style="padding: 4px; font-size: 10px;">Add: IGST</td><td style="text-align: right; font-weight: bold; padding: 4px;">{{ $bill->igst > 0 ? number_format($bill->igst, 2) : '—' }}</td></tr>
                    <tr class="total-box"><td style="padding: 6px;">Total Amount</td><td style="text-align: right; padding: 6px;">₹ {{ number_format($bill->total, 0) }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Footer --}}
    <div style="margin-top: 5px;">
        <div class="guarantee">NO DYEING GUARANTEE</div>
        <table style="width: 100%; border: 1px solid #0056b3; border-top: none;">
            <tr>
                <td style="width: 65%; padding: 10px; vertical-align: top;">
                    <div style="font-weight: bold;">Amount in Words :</div>
                    <div style="margin-bottom: 10px;">{{ App\Helpers\NumberHelper::amountInWords($bill->total) }}</div>
                    
                    <div style="border: 1px dashed #0056b3; padding: 8px; font-size: 10px;">
                        <strong>Bank Details:</strong><br>
                        Bank Name: {{ $bill->shop?->bank_name ?? '—' }}<br>
                        A/c. No.: {{ $bill->shop?->account_number ?? '—' }}<br>
                        IFSC Code: {{ $bill->shop?->ifsc_code ?? '—' }}
                    </div>
                </td>
                <td style="width: 35%; padding: 10px; vertical-align: bottom; text-align: center;">
                    <div style="font-size: 9px; margin-bottom: 40px;">Certified that the particulars given above are true & correct</div>
                    <div style="font-weight: bold;">FOR, {{ strtoupper($bill->shop?->name) }}</div>
                    <div style="height: 40px;"></div>
                    <div style="border-top: 1px solid #0056b3; display: inline-block; padding-top: 5px;">Authorised Signatory</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Terms --}}
    <div style="font-size: 8px; border: 1px solid #0056b3; border-top: none; padding: 5px;">
        <strong>TERMS:</strong> (1) Payment by A/c. Payee Cheque or Draft. (2) Complaints within 5 days. (3) 24% Interest after due date. (4) Not responsible for transit damage. (5) Sold goods not returnable. (6) Surat Jurisdiction.
    </div>
</div>
</body>
</html>
