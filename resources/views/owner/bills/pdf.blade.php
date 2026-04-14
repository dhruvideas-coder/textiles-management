<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TAX INVOICE - {{ $bill->bill_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; color: #000; }
        .page { width: 100%; padding: 8px 10px; }
        table { border-collapse: collapse; }
        .items-table { width: 100%; }
        .items-table th {
            background: #1a3a6b;
            color: #fff;
            border: 1px solid #000;
            padding: 4px 5px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
        }
        .items-table td { border: 1px solid #000; padding: 4px 5px; vertical-align: top; }
        .no-dyeing { font-weight: bold; font-size: 13px; text-align: center; color: #cc0000; letter-spacing: 1px; }
    </style>
</head>
<body>
<div class="page">

    {{-- ===== TOP ROW: Religious text | TAX INVOICE stamp | Mobile ===== --}}
    <table style="width:100%; margin-bottom:4px;">
        <tr>
            <td style="width:30%; font-size:9px; text-align:center; line-height:1.6;">
                &#2404;&#2404; &#2358;&#2381;&#2352;&#2368; &#2327;&#2339;&#2375;&#2358;&#2366;&#2351; &#2344;&#2350;&#2307; &#2404;&#2404;<br>
                &#2404;&#2404; &#2332;&#2351; &#2348;&#2354;&#2367;&#2351;&#2366;&#2352; &#2350;&#2366;&#2340;&#2366;&#2351; &#2344;&#2350;&#2307; &#2404;&#2404;
            </td>
            <td style="width:40%; text-align:center;">
                <div style="border:2px solid #000; display:inline-block; padding:3px 18px; font-weight:bold; font-size:13px; letter-spacing:1px;">TAX INVOICE</div>
            </td>
            <td style="width:30%; text-align:right; font-size:11px; font-weight:bold; vertical-align:middle;">
                Mo.: {{ $bill->shop?->phone ?? '98790 69490' }}
            </td>
        </tr>
    </table>

    {{-- ===== COMPANY HEADER ===== --}}
    <table style="width:100%; margin-bottom:2px;">
        <tr>
            <td style="width:68%; text-align:center; vertical-align:middle; padding:4px 0;">
                @php
                    $shopNameParts = explode(' ', $bill->shop?->name ?? 'GURUDEV TEXTILES', 2);
                    $shopFirst = $shopNameParts[0];
                    $shopRest  = $shopNameParts[1] ?? '';
                @endphp
                <span style="font-size:44px; font-weight:bold; color:#cc0000; line-height:1;">{{ strtoupper($shopFirst) }}</span>
                @if($shopRest)
                <span style="font-size:34px; font-weight:bold; color:#000; line-height:1;">&nbsp;&nbsp;{{ strtoupper($shopRest) }}</span>
                @endif
                <span style="font-size:22px; color:#000; vertical-align:middle;">&nbsp;&#9552;&#9552;</span>
            </td>
            <td style="width:32%; vertical-align:middle; padding-left:5px;">
                <div style="border:2px solid #cc0000; padding:6px 8px; text-align:center; font-size:10px; font-weight:bold; line-height:1.6;">
                    Manufacturer &amp; Dealers in :<br>
                    <span style="font-size:13px; letter-spacing:1px;">ART SILK CLOTH</span>
                </div>
            </td>
        </tr>
    </table>

    {{-- ===== ADDRESS BAR ===== --}}
    <div style="border-top:1.5px solid #000; border-bottom:1.5px solid #000; text-align:center; padding:3px 0; font-size:9px; margin-bottom:5px;">
        {{ $bill->shop?->address ?? 'Plot No. 38, Sonal Ind. Estate-3, G.H.B. Road, Behind Chickuwadi, Near New Water Tank' }}, {{ $bill->shop?->city ?? 'SURAT' }}.
    </div>

    {{-- ===== GSTIN + HSN ROW ===== --}}
    <table style="width:100%;">
        <tr>
            <td style="width:55%; border:1px solid #000; padding:3px 6px; font-size:10px; font-weight:bold;">
                GSTIN No.: {{ $bill->shop?->gstin ?? '24EZAPP5247K1Z3' }}
            </td>
            <td style="width:20%; border:1px solid #000; border-left:none; padding:3px 6px; font-size:10px;">
                HSN Code :
            </td>
            <td style="width:25%; border:1px solid #000; border-left:none; padding:3px 6px; font-size:10px;">
                &nbsp;
            </td>
        </tr>
    </table>

    {{-- ===== NAME + BILL NO ===== --}}
    <table style="width:100%;">
        <tr>
            <td style="width:55%; border:1px solid #000; border-top:none; padding:3px 6px; font-size:10px;">
                <strong>Name :</strong>&nbsp; {{ $bill->customer?->name ?? '' }}
            </td>
            <td style="width:20%; border:1px solid #000; border-top:none; border-left:none; padding:3px 6px; font-size:10px;">
                Bill No. : <strong>{{ $bill->bill_number }}</strong>
            </td>
            <td style="width:25%; border:1px solid #000; border-top:none; border-left:none; padding:3px 6px; font-size:10px;">
                &nbsp;
            </td>
        </tr>
    </table>

    {{-- ===== ADDRESS + DATE ===== --}}
    <table style="width:100%;">
        <tr>
            <td style="width:55%; border:1px solid #000; border-top:none; padding:3px 6px; font-size:10px;">
                <strong>Address :</strong>&nbsp;
                @if($bill->customer?->address){{ $bill->customer->address }}@endif
                @if($bill->customer?->city), {{ $bill->customer->city }}@endif
            </td>
            <td style="width:20%; border:1px solid #000; border-top:none; border-left:none; padding:3px 6px; font-size:10px;">
                Date :
            </td>
            <td style="width:25%; border:1px solid #000; border-top:none; border-left:none; padding:3px 6px; font-size:10px;">
                {{ $bill->bill_date?->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    {{-- ===== ADDRESS LINE 2 + CHALLAN NO ===== --}}
    <table style="width:100%;">
        <tr>
            <td style="width:55%; border:1px solid #000; border-top:none; padding:3px 6px; font-size:10px;">
                &nbsp;
            </td>
            <td style="width:20%; border:1px solid #000; border-top:none; border-left:none; padding:3px 6px; font-size:10px;">
                Challan No.
            </td>
            <td style="width:25%; border:1px solid #000; border-top:none; border-left:none; padding:3px 6px; font-size:10px;">
                &nbsp;
            </td>
        </tr>
    </table>

    {{-- ===== CUSTOMER GSTIN + BROKER ===== --}}
    <table style="width:100%;">
        <tr>
            <td style="width:55%; border:1px solid #000; border-top:none; padding:3px 6px; font-size:10px;">
                <strong>GSTIN :</strong>&nbsp; {{ $bill->customer?->gstin ?? '' }}
            </td>
            <td colspan="2" style="width:45%; border:1px solid #000; border-top:none; border-left:none; padding:3px 6px; font-size:10px;">
                Broker &nbsp; {{ $bill->notes ?? '' }}
            </td>
        </tr>
    </table>

    {{-- ===== ITEMS TABLE ===== --}}
    <table class="items-table" style="margin-top:5px;">
        <thead>
            <tr>
                <th style="width:28px;">Sr.<br>No.</th>
                <th style="text-align:left;">Product Description</th>
                <th style="width:55px;">Total<br>Pcs.</th>
                <th style="width:70px;">Total<br>Meters</th>
                <th style="width:60px;">Rate</th>
                <th style="width:85px;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $itemCount = 0; @endphp
            @foreach($bill->items as $i => $item)
            @php $itemCount++; @endphp
            <tr>
                <td style="text-align:center;">{{ $i + 1 }}</td>
                <td style="padding-left:8px;">{{ $item->description }}</td>
                <td style="text-align:center;">{{ $item->pieces ?? '' }}</td>
                <td style="text-align:center;">{{ $item->meters ? number_format($item->meters, 2) : '' }}</td>
                <td style="text-align:center;">{{ $item->rate ? number_format($item->rate, 2) : '' }}</td>
                <td style="text-align:right; padding-right:6px;">{{ $item->amount ? number_format($item->amount, 2) : '' }}</td>
            </tr>
            @endforeach
            @for($blank = $itemCount; $blank < 8; $blank++)
            <tr>
                <td style="height:18px;">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            @endfor
        </tbody>
    </table>

    {{-- ===== GOODS DELIVERED + DUE DATE SECTION ===== --}}
    <table style="width:100%;">
        <tr>
            <td style="width:55%; border:1px solid #000; border-top:none; padding:5px 6px; font-size:10px; height:22px;">
                Goods Delivered to &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width:45%; border:1px solid #000; border-top:none; border-left:none; padding:5px 6px; font-size:10px;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td style="border:1px solid #000; border-top:none; padding:5px 6px; font-size:10px; height:22px;">
                &nbsp;
            </td>
            <td style="border:1px solid #000; border-top:none; border-left:none; padding:5px 6px; font-size:10px;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td style="border:1px solid #000; border-top:none; padding:5px 6px; font-size:10px; height:22px;">
                Due Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="border:1px solid #000; border-top:none; border-left:none; padding:5px 6px; font-size:10px;">
                &nbsp;
            </td>
        </tr>
    </table>

    {{-- ===== FOOTER: NO DYEING + TAX TOTALS ===== --}}
    <table style="width:100%; margin-top:4px;">
        <tr>
            <td style="width:54%; border:1px solid #000; vertical-align:top; padding:6px;">
                <div class="no-dyeing">NO DYEING GURANTEE</div>
                <div style="margin-top:10px; font-size:10px;">Amount in Words : ___________________________________</div>
                <div style="margin-top:18px;">
                    <div style="font-size:10px;">Bank Name &nbsp;: _________________________________</div>
                    <div style="font-size:10px; margin-top:6px;">A/c. No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : _________________________________</div>
                    <div style="font-size:10px; margin-top:6px;">IFSC Code &nbsp; : _________________________________</div>
                </div>
            </td>
            <td style="width:46%; vertical-align:top; padding:0;">
                <table style="width:100%;">
                    <tr>
                        <td style="border:1px solid #000; padding:3px 6px; font-size:10px;">Total Amount Before Tax</td>
                        <td style="border:1px solid #000; border-left:none; padding:3px 6px; font-size:10px; text-align:right; min-width:70px;">
                            @if($bill->subtotal > 0){{ number_format($bill->subtotal, 2) }}@endif
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; border-top:none; padding:3px 6px; font-size:10px;">Add : CGST &nbsp; 2.5 %</td>
                        <td style="border:1px solid #000; border-top:none; border-left:none; padding:3px 6px; font-size:10px; text-align:right;">
                            @if($bill->cgst > 0){{ number_format($bill->cgst, 2) }}@endif
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; border-top:none; padding:3px 6px; font-size:10px;">Add : SGST &nbsp; 2.5 %</td>
                        <td style="border:1px solid #000; border-top:none; border-left:none; padding:3px 6px; font-size:10px; text-align:right;">
                            @if($bill->sgst > 0){{ number_format($bill->sgst, 2) }}@endif
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; border-top:none; padding:3px 6px; font-size:10px;">Add : IGST &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %</td>
                        <td style="border:1px solid #000; border-top:none; border-left:none; padding:3px 6px; font-size:10px; text-align:right;">
                            @if($bill->igst > 0){{ number_format($bill->igst, 2) }}@endif
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; border-top:none; padding:4px 6px; font-size:11px; font-weight:bold;">Total Amount After Tax</td>
                        <td style="border:1px solid #000; border-top:none; border-left:none; padding:4px 6px; font-size:11px; font-weight:bold; text-align:right;">
                            {{ number_format($bill->total, 2) }}
                        </td>
                    </tr>
                </table>
                <div style="font-size:9px; text-align:right; padding:4px 6px; line-height:1.5;">
                    Certified that the particulars given above are true &amp; correct
                </div>
                <div style="font-size:11px; font-weight:bold; text-align:right; padding:6px 6px 0 6px;">
                    FOR, {{ strtoupper($bill->shop?->name ?? 'GURUDEV TEXTILES') }}
                </div>
                <div style="height:35px;"></div>
                <div style="font-size:9px; text-align:center; padding:0 6px 4px 6px;">
                    Authorised Signatory
                </div>
            </td>
        </tr>
    </table>

    {{-- ===== TERMS OF CONDITION ===== --}}
    <table style="width:100%; margin-top:5px;">
        <tr>
            <td style="width:65%; border:1px solid #000; padding:4px 5px; font-size:8px; vertical-align:top; line-height:1.5;">
                <strong>TERMS OF CONDITION :</strong>
                (1) Payment to be made by A/c. Payee's Cheque or Draft only.
                (2) Any Complaint for goods should be made within 5 days after that no complaints will be entertained.
                (3) Interest at 24% per annum will be charged after due date of the bill.
                (4) We are not responsible for any loss or damage during transit.
                (5) Sold goods will not be taken back in any Condition.
                (6) Subject to Surat Jurisdiction only.
            </td>
            <td style="width:35%; border:1px solid #000; border-left:none; padding:4px 5px; font-size:9px; vertical-align:bottom; text-align:center;">
                Receiver Sign. ____________________
            </td>
        </tr>
    </table>

</div>
</body>
</html>
