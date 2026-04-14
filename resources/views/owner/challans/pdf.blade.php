<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>DELIVERY CHALLAN - {{ $challan->challan_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; color: #000; }
        .page { width: 100%; padding: 8px 10px; }
        table { border-collapse: collapse; }
        .grid-table { width: 100%; }
        .grid-table th {
            background: #1a3a6b;
            color: #fff;
            border: 1px solid #000;
            padding: 3px 4px;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
        }
        .grid-table td {
            border: 1px solid #000;
            padding: 3px 4px;
            text-align: center;
            font-size: 9px;
        }
        .no-dyeing { font-weight: bold; font-size: 11px; color: #cc0000; letter-spacing: 1px; }
    </style>
</head>
<body>
<div class="page">

    {{-- ===== TOP ROW: DELIVERY CHALLN | Religious text | Mobile ===== --}}
    <table style="width:100%; margin-bottom:4px;">
        <tr>
            <td style="width:30%; vertical-align:middle;">
                <div style="border:2px solid #000; display:inline-block; padding:3px 10px; font-weight:bold; font-size:12px; letter-spacing:1px;">DELIVERY CHALLN</div>
            </td>
            <td style="width:40%; text-align:center; font-size:9px; line-height:1.6; vertical-align:middle;">
                &#2404;&#2404; &#2358;&#2381;&#2352;&#2368; &#2327;&#2339;&#2375;&#2358;&#2366;&#2351; &#2344;&#2350;&#2307; &#2404;&#2404;
            </td>
            <td style="width:30%; text-align:right; font-size:11px; font-weight:bold; vertical-align:middle;">
                Mo.: {{ $challan->shop?->phone ?? '98790 69490' }}
            </td>
        </tr>
    </table>

    {{-- ===== COMPANY HEADER ===== --}}
    <table style="width:100%; margin-bottom:2px;">
        <tr>
            <td style="text-align:center; padding:4px 0;">
                <div>
                    @php
                        $shopNameParts = explode(' ', $challan->shop?->name ?? 'GURUDEV TEXTILES', 2);
                        $shopFirst = $shopNameParts[0];
                        $shopRest  = $shopNameParts[1] ?? '';
                    @endphp
                    <span style="font-size:44px; font-weight:bold; color:#cc0000; line-height:1;">{{ strtoupper($shopFirst) }}</span>
                    @if($shopRest)
                    <span style="font-size:34px; font-weight:bold; color:#000; line-height:1;">&nbsp;&nbsp;{{ strtoupper($shopRest) }}</span>
                    @endif
                </div>
                <div style="border:1.5px solid #000; display:inline-block; padding:3px 20px; font-size:11px; font-weight:bold; margin-top:4px; letter-spacing:1px;">
                    Manufacturer &amp; Dealers in : ART SILK CLOTH
                </div>
            </td>
        </tr>
    </table>

    {{-- ===== ADDRESS BAR ===== --}}
    <div style="border-top:1.5px solid #000; border-bottom:1.5px solid #000; text-align:center; padding:3px 0; font-size:9px; margin-bottom:5px;">
        {{ $challan->shop?->address ?? 'Plot No. 38, Sonal Ind. Estate-3, G.H.B. Road, Behind Chickuwadi, Near New Water Tank' }}, {{ $challan->shop?->city ?? 'SURAT' }}.
    </div>

    {{-- ===== M/S + CHALLAN NO ===== --}}
    <table style="width:100%;">
        <tr>
            <td style="width:62%; border:1px solid #000; padding:3px 6px; font-size:10px;">
                <strong>M/s. :</strong>&nbsp; {{ $challan->party_name ?? $challan->customer?->name ?? '' }}
            </td>
            <td style="width:18%; border:1px solid #000; border-left:none; padding:3px 6px; font-size:10px;">
                Challan No. :
            </td>
            <td style="width:20%; border:1px solid #000; border-left:none; padding:3px 6px; font-size:10px; font-weight:bold;">
                {{ $challan->challan_number }}
            </td>
        </tr>
    </table>

    {{-- ===== ADDRESS + DATE ===== --}}
    <table style="width:100%;">
        <tr>
            <td style="width:62%; border:1px solid #000; border-top:none; padding:3px 6px; font-size:10px;">
                <strong>Add. :</strong>&nbsp;
                @if($challan->customer?->address){{ $challan->customer->address }}@endif
                @if($challan->customer?->city), {{ $challan->customer->city }}@endif
            </td>
            <td style="width:18%; border:1px solid #000; border-top:none; border-left:none; padding:3px 6px; font-size:10px;">
                Date :
            </td>
            <td style="width:20%; border:1px solid #000; border-top:none; border-left:none; padding:3px 6px; font-size:10px;">
                {{ $challan->challan_date?->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    {{-- ===== BROKER ROW ===== --}}
    <table style="width:100%;">
        <tr>
            <td style="width:62%; border:1px solid #000; border-top:none; padding:3px 6px; font-size:10px;">
                &nbsp;
            </td>
            <td colspan="2" style="width:38%; border:1px solid #000; border-top:none; border-left:none; padding:3px 6px; font-size:10px;">
                <strong>Broker :</strong>&nbsp; {{ $challan->broker_name ?? '' }}
            </td>
        </tr>
    </table>

    {{-- ===== ITEMS GRID ===== --}}
    @php
        $items = $challan->items;
        $totalPcs = 0;
        $totalMeters = 0;
        $totalWeight = 0;
        foreach ($items as $item) {
            $totalPcs += (int)($item->pieces ?? 0);
            $totalMeters += (float)($item->meters ?? 0);
            $totalWeight += (float)($item->weight ?? 0);
        }
    @endphp

    <table style="width:100%; margin-top:5px;">
        <tr>
            <td style="width:72%; vertical-align:top; padding-right:4px;">

                {{-- Quality label row --}}
                <table class="grid-table" style="margin-bottom:0;">
                    <thead>
                        <tr>
                            <th style="width:24px;">Sr.<br>No.</th>
                            <th style="text-align:left; padding-left:6px;">Quality / Product</th>
                            <th style="width:45px;">Pcs.</th>
                            <th style="width:65px;">Meters</th>
                            <th style="width:60px;">Weight<br>(Kgs)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td style="text-align:left; padding-left:6px;">{{ $item->product_name }}</td>
                            <td>{{ $item->pieces ?? '-' }}</td>
                            <td>{{ $item->meters ? number_format($item->meters, 2) : '-' }}</td>
                            <td>{{ $item->weight ? number_format($item->weight, 2) : '-' }}</td>
                        </tr>
                        @endforeach
                        @for($blank = count($items); $blank < 10; $blank++)
                        <tr>
                            <td style="height:16px;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>

                {{-- Totals row --}}
                <table class="grid-table" style="margin-top:0;">
                    <tr>
                        <td style="width:24px; font-weight:bold; font-size:9px; background:#f0f0f0;">Total</td>
                        <td style="text-align:right; padding-right:8px; font-weight:bold; font-size:9px; background:#f0f0f0;">
                            &nbsp;
                        </td>
                        <td style="width:45px; font-weight:bold; font-size:9px; background:#f0f0f0;">{{ $totalPcs }}</td>
                        <td style="width:65px; font-weight:bold; font-size:9px; background:#f0f0f0;">{{ number_format($totalMeters, 2) }}</td>
                        <td style="width:60px; font-weight:bold; font-size:9px; background:#f0f0f0;">{{ number_format($totalWeight, 2) }}</td>
                    </tr>
                </table>

            </td>

            {{-- ===== RIGHT SUMMARY BOX ===== --}}
            <td style="width:28%; vertical-align:top;">
                <table style="width:100%; border:1px solid #000;">
                    <tr>
                        <td colspan="2" style="border-bottom:1px solid #000; padding:3px 5px; font-size:9px; font-weight:bold; background:#f0f0f0; text-align:center;">SUMMARY</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:3px 5px; font-size:9px;">Total Pcs.</td>
                        <td style="border-bottom:1px solid #000; border-left:1px solid #000; padding:3px 5px; font-size:10px; font-weight:bold; text-align:right;">{{ $totalPcs }}</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:3px 5px; font-size:9px;">Total Mts.</td>
                        <td style="border-bottom:1px solid #000; border-left:1px solid #000; padding:3px 5px; font-size:10px; font-weight:bold; text-align:right;">{{ number_format($totalMeters, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:3px 5px; font-size:9px;">Total Kgs.</td>
                        <td style="border-bottom:1px solid #000; border-left:1px solid #000; padding:3px 5px; font-size:10px; font-weight:bold; text-align:right;">{{ number_format($totalWeight, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:3px 5px; font-size:9px;">Total Weight :</td>
                        <td style="border-bottom:1px solid #000; border-left:1px solid #000; padding:3px 5px; font-size:9px; text-align:right;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:3px 5px; font-size:9px;">By :</td>
                        <td style="border-bottom:1px solid #000; border-left:1px solid #000; padding:3px 5px; font-size:9px; text-align:right;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-bottom:1px solid #000; padding:3px 5px; font-size:9px;">Remarks :</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:3px 5px; font-size:9px; height:30px;">
                            {{ $challan->remarks ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-top:1px solid #000; padding:4px 5px; text-align:center;">
                            <span class="no-dyeing">No Dyeing Guarantee</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ===== FOOTER ===== --}}
    <table style="width:100%; margin-top:8px;">
        <tr>
            <td style="width:55%; border:1px solid #000; padding:5px 6px; font-size:8px; vertical-align:top; line-height:1.6;">
                <strong>N.B. :</strong> Report to be Presented within 24 Hours. after that it will not be entertained.
                <br><br>
                <span style="font-size:8px; color:#333;">
                    &#2332;&#2357;&#2366;&#2348;&#2342;&#2366;&#2352; &#2357;&#2381;&#2351;&#2325;&#2381;&#2340;&#2367;&#2319; &#2360;&#2350;&#2351; &#2360;&#2381;&#2335;&#2375;&#2350;&#2381;&#2346; &#2350;&#2366;&#2352;&#2368; &#2357;&#2366;&#2306;&#2343;&#2366; &#2360;&#2366;&#2330;&#2368; &#2332;&#2366;&#2339; &#2341;&#2366;&#2351;
                </span>
            </td>
            <td style="width:45%; border:1px solid #000; border-left:none; padding:5px 6px; font-size:9px; vertical-align:top; text-align:center; line-height:1.8;">
                Received the above goods in good and sound condition.
                <br><br>
                <span style="font-size:8px; color:#333;">
                    &#2313;&#2346;&#2352; &#2349;&#2367; &#2355;&#2340;&#2369;&#2360;&#2381; &#2350;&#2375;&#2355; &#2358;&#2352;&#2381;&#2340;&#2368; &#2350;&#2366;&#2354; &#2350;&#2333;&#2369;&#2360; &#2350;&#2347;&#2332;&#2366;&#2357;&#2375;&#2354; &#2341;&#2351;&#2366;&#2306;
                </span>
            </td>
        </tr>
        <tr>
            <td style="border:1px solid #000; border-top:none; padding:4px 6px; font-size:10px;">
                Prepared by : ________________________________
            </td>
            <td style="border:1px solid #000; border-top:none; border-left:none; padding:4px 6px; font-size:10px; text-align:center;">
                Receiver's Signature : ________________
            </td>
        </tr>
    </table>

</div>
</body>
</html>
