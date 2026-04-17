<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Challan - {{ $challan->challan_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; background: #fff; padding: 20px; }
        .header { text-align: center; padding-bottom: 10px; border-bottom: 2px solid #222; }
        .header h1 { font-size: 24px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; }
        .header p { font-size: 10px; margin-top: 3px; color: #555; }
        .header .gstin { font-weight: bold; color: #111; }
        .info-row { display: table; width: 100%; margin: 12px 0; border-bottom: 2px solid #222; padding-bottom: 12px; }
        .info-left { display: table-cell; width: 50%; vertical-align: top; }
        .info-right { display: table-cell; width: 50%; vertical-align: top; text-align: right; }
        .info-left p, .info-right p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #222; padding: 4px 6px; text-align: center; }
        thead th { background-color: #e5e5e5; font-weight: bold; }
        tfoot td { font-weight: bold; background-color: #f0f0f0; }
        .totals-bar { display: table; width: 100%; margin-top: 12px; border: 2px solid #222; padding: 6px 8px; }
        .totals-bar-left { display: table-cell; font-weight: bold; font-size: 13px; }
        .totals-bar-right { display: table-cell; text-align: right; font-weight: bold; font-size: 13px; }
        .footer { display: table; width: 100%; margin-top: 50px; padding: 0 10px; }
        .footer-left { display: table-cell; vertical-align: bottom; font-size: 10px; }
        .footer-right { display: table-cell; text-align: center; font-weight: bold; vertical-align: bottom; }
        .sig-space { height: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Gurudev Textiles</h1>
        <p>Surat, Gujarat - 395002 | Mobile: +91 99999 99999</p>
        <p class="gstin">GSTIN: 24AAAAA0000A1Z5</p>
    </div>

    <div class="info-row">
        <div class="info-left">
            <p><strong>M/S:</strong> {{ $challan->customer?->name }}</p>
            <p>{{ $challan->customer?->address }}</p>
            <p><strong>GSTIN:</strong> {{ $challan->customer?->GSTIN }}</p>
            <p><strong>Mobile:</strong> {{ $challan->customer?->mobile_number }}</p>
        </div>
        <div class="info-right">
            <p><strong>Challan No:</strong> {{ $challan->challan_number }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($challan->date)->format('d/m/Y') }}</p>
            <p><strong>Quality:</strong> {{ $challan->product?->name }}</p>
            <p><strong>Broker:</strong> {{ $challan->broker }}</p>
        </div>
    </div>

    <div>
        <p style="font-weight:bold; margin-bottom:6px;">Measurement (6x12 Grid)</p>
        <table>
            <thead>
                <tr>
                    <th>Row</th>
                    @for($c = 1; $c <= 6; $c++)
                    <th>Col {{ $c }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @php
                    $grid = [];
                    foreach($challan->items as $item) {
                        $grid[$item->row_no][$item->column_no] = $item->meters;
                    }
                    $colTotals = array_fill(1, 6, 0);
                @endphp
                @for($r = 1; $r <= 12; $r++)
                <tr>
                    <td style="font-weight:bold;">{{ $r }}</td>
                    @for($c = 1; $c <= 6; $c++)
                        @php
                            $val = $grid[$r][$c] ?? 0;
                            $colTotals[$c] += floatval($val);
                        @endphp
                    <td>{{ floatval($val) > 0 ? floatval($val) : '' }}</td>
                    @endfor
                </tr>
                @endfor
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td>
                    @for($c = 1; $c <= 6; $c++)
                    <td>{{ $colTotals[$c] > 0 ? $colTotals[$c] : '' }}</td>
                    @endfor
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="totals-bar">
        <div class="totals-bar-left">TOTAL PIECES: {{ $challan->total_pieces }}</div>
        <div class="totals-bar-right">GRAND TOTAL METERS: {{ $challan->total_meters }}</div>
    </div>

    <div class="footer">
        <div class="footer-left">
            <p style="font-weight:bold; text-transform:uppercase;">* No Dyeing Guarantee *</p>
            <p>Goods once sold will not be taken back.</p>
        </div>
        <div class="footer-right">
            <p>For Gurudev Textiles</p>
            <div class="sig-space"></div>
            <p>Authorised Signatory</p>
        </div>
    </div>
</body>
</html>
