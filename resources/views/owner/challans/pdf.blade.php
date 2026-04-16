<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CHALLAN - {{ $challan->challan_number }}</title>
    <style>
        @page { margin: 10px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #001; }
        .container { border: 2px solid #0056b3; padding: 8px; position: relative; min-height: 98vh; }
        
        /* Header */
        .top-bar { display: flex; justify-content: space-between; border-bottom: 2px solid #0056b3; padding-bottom: 4px; margin-bottom: 4px; }
        .header-main { text-align: center; color: #0056b3; }
        .shop-name { font-size: 32px; font-weight: 900; letter-spacing: 2px; }
        .shop-tagline { border: 1.5px solid #0056b3; background: #0056b3; color: #fff; padding: 2px 15px; font-weight: bold; font-size: 10px; border-radius: 20px; display: inline-block; margin-top: 2px; }
        .address-bar { text-align: center; font-size: 9px; border-bottom: 2px solid #0056b3; padding: 3px 0; margin-bottom: 5px; }
        
        /* Information Grid */
        table { width: 100%; border-collapse: collapse; }
        .info-table td { border: 1px solid #0056b3; padding: 4px 6px; }
        .label { font-weight: bold; width: 80px; }
        
        /* Product Table */
        .items-table th, .items-table td { border: 1px solid #0056b3; padding: 3px; text-align: center; }
        .items-table th { background: #f0f7ff; font-weight: bold; font-size: 10px; }
        
        .measurements-grid { width: 75%; float: left; }
        .summary-box { width: 25%; float: left; border: 1px solid #0056b3; border-left: none; }
        .summary-row { display: flex; border-bottom: 1px solid #0056b3; padding: 4px; }
        .summary-row .label { font-size: 9px; flex: 1; }
        .summary-row .value { text-align: right; font-weight: bold; width: 60px; }
        
        .footer { position: absolute; bottom: 8px; left: 8px; right: 8px; }
        .clear { clear: both; }
        
        .guarantee { color: #d00; font-weight: bold; font-size: 12px; font-style: italic; border: 1px solid #d00; padding: 2px 8px; display: inline-block; margin-top: 10px; }
        .gujarati { font-family: 'Gujarati', sans-serif; font-size: 9px; color: #444; }
    </style>
</head>
<body>
<div class="container">
    {{-- Top Strip --}}
    <table style="width: 100%; border-bottom: 2px solid #0056b3; margin-bottom: 4px;">
        <tr>
            <td style="width: 30%;"><div style="border: 1.5px solid #0056b3; padding: 2px 8px; font-weight: bold; display: inline-block;">DELIVERY CHALLAN</div></td>
            <td style="width: 40%; text-align: center; font-size: 10px;">॥ શ્રી ગણેશાય નમઃ ॥</td>
            <td style="width: 30%; text-align: right; font-weight: bold;">Mo.: {{ $challan->shop?->phone }}</td>
        </tr>
    </table>

    {{-- Shop Header --}}
    <div class="header-main" style="margin-bottom: 5px;">
        <div class="shop-name">{{ strtoupper($challan->shop?->name) }}</div>
        <div class="shop-tagline">{{ $challan->shop?->tagline ?? 'Manufacturer & Dealers in : ART SILK CLOTH' }}</div>
    </div>

    <div class="address-bar">
        {{ $challan->shop?->address }}, {{ $challan->shop?->city }}-{{ $challan->shop?->pincode }} ({{ $challan->shop?->state }})
    </div>

    {{-- Details --}}
    <table class="info-table" style="margin-bottom: 8px;">
        <tr>
            <td style="width: 65%;">
                <span class="label">M/s.</span> <span style="font-size: 14px; font-weight: 900;">{{ strtoupper($challan->party_name) }}</span><br>
                <span class="label">Add.</span> {{ $challan->customer?->address ?? '—' }}<br>
                <span class="label">GSTIN No.</span> {{ $challan->customer?->gstin ?? '—' }}
            </td>
            <td style="width: 35%; vertical-align: top;">
                <table style="width: 100%;">
                    <tr><td class="label" style="border:none;">Challan No.</td><td style="border:none; font-weight: bold;">{{ $challan->challan_number }}</td></tr>
                    <tr><td class="label" style="border:none;">Date</td><td style="border:none;">{{ $challan->challan_date?->format('d/m/Y') }}</td></tr>
                    <tr><td class="label" style="border:none;">Broker</td><td style="border:none;">{{ $challan->broker_name ?? '—' }}</td></tr>
                    <tr><td class="label" style="border:none;">Order No.</td><td style="border:none;">{{ $challan->order_number ?? '—' }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Items --}}
    @foreach($challan->items as $item)
    <div style="margin-bottom: 10px;">
        <div style="background: #f0f7ff; padding: 4px; border: 1px solid #0056b3; border-bottom: none; font-weight: bold;">
            Quality: {{ $item->product_name }}
        </div>
        <div class="measurements-grid">
            <table class="items-table">
                <tbody>
                    @php
                        $measurements = is_array($item->measurements) ? $item->measurements : [];
                        $chunks = array_chunk($measurements, 6); // 6 columns
                    @endphp
                    @for($r = 0; $r < 12; $r++)
                        <tr>
                            <td style="width: 30px; background: #fafafa; font-size: 9px; border-color: #ddd;">{{ $r + 1 }}</td>
                            @for($c = 0; $c < 6; $c++)
                                @php $val = $measurements[$r * 6 + $c] ?? ''; @endphp
                                <td style="width: 16.66%; height: 22px; font-weight: bold;">{{ $val ?: '—' }}</td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        <div class="summary-box">
             <div class="summary-row"><div class="label">Total Pcs.</div><div class="value">{{ $item->pieces }}</div></div>
             <div class="summary-row"><div class="label">Total Mts.</div><div class="value">{{ number_format($item->meters, 2) }}</div></div>
             <div class="summary-row"><div class="label">Total Kgs.</div><div class="value">{{ number_format($item->weight, 2) }}</div></div>
             <div class="summary-row" style="height: 30px;"><div class="label">Remarks</div><div class="value" style="font-size: 8px;">{{ $item->remarks }}</div></div>
             <div style="text-align: center; padding: 10px;">
                <span style="color: #0056b3; font-weight: bold; border-bottom: 1px solid #0056b3;">No Dyeing Guarantee</span>
             </div>
        </div>
        <div class="clear"></div>
        <table style="width: 75%; border: 1px solid #0056b3; border-top: none;">
            <tr style="background: #0056b3; color: white;">
                <td style="width: 30px; padding: 4px; font-weight: bold;">TOTAL</td>
                @for($c = 0; $c < 6; $c++)
                    @php 
                        $colTotal = 0;
                        for($r = 0; $r < 12; $r++) {
                            $colTotal += (float) ($measurements[$r * 6 + $c] ?? 0);
                        }
                    @endphp
                    <td style="width: 16.66%; font-weight: bold;">{{ $colTotal ?: '—' }}</td>
                @endfor
            </tr>
        </table>
    </div>
    @endforeach

    <div class="footer">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; border: 1.5px solid #0056b3; padding: 10px;">
                    <div style="font-size: 8px; line-height: 1.5;">
                        <strong>N.B.:</strong> Report to be Presented within 24 Hours.<br>
                        after that it will not be entertained.<br>
                        <span class="gujarati">(જવાબદાર વ્યક્તિએ રબર સ્ટેમ્પ મારી વાંચી શકાય તે પ્રમાણે સહી કરવી.)</span>
                    </div>
                    <div style="margin-top: 15px;">Prepared by: _____________________</div>
                </td>
                <td style="width: 50%; border: 1.5px solid #0056b3; border-left: none; padding: 10px;">
                    <div style="font-size: 9px; text-align: center;">
                        Received the above goods in good and sound condition.
                    </div>
                    <div style="margin-top: 25px; text-align: center;">Receiver's Signature: ________________</div>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
