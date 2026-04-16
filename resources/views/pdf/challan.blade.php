<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Challan - {{ $challan->challan_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Arial', sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-900 p-8 text-sm max-w-4xl mx-auto border-2 border-gray-800 m-4">
    <div class="text-center pb-4 border-b-2 border-gray-800">
        <h1 class="text-3xl font-bold uppercase tracking-wider text-gray-900">Gurudev Textiles</h1>
        <p class="text-xs mt-1 font-semibold text-gray-600">Surat, Gujarat - 395002 | Mobile: +91 99999 99999</p>
        <p class="text-xs font-bold mt-1">GSTIN: 24AAAAA0000A1Z5</p>
    </div>

    <div class="flex justify-between py-4 border-b-2 border-gray-800">
        <div class="w-1/2">
            <h2 class="font-bold text-gray-700">M/S: {{ $challan->customer?->name }}</h2>
            <p>{{ $challan->customer?->address }}</p>
            <p><strong>GSTIN:</strong> {{ $challan->customer?->GSTIN }}</p>
            <p><strong>Mobile:</strong> {{ $challan->customer?->mobile_number }}</p>
        </div>
        <div class="w-1/2 text-right">
            <p><span class="font-bold">Challan No:</span> {{ $challan->challan_number }}</p>
            <p><span class="font-bold">Date:</span> {{ \Carbon\Carbon::parse($challan->date)->format('d/m/Y') }}</p>
            <p><span class="font-bold">Quality:</span> {{ $challan->product?->name }}</p>
            <p><span class="font-bold">Broker:</span> {{ $challan->broker }}</p>
        </div>
    </div>

    <div class="mt-4">
        <h3 class="font-bold mb-2">Measurement (6x12 Grid)</h3>
        <table class="w-full border-collapse border border-gray-800 text-center">
            <thead>
                <tr>
                    <th class="border border-gray-800 p-1">Row</th>
                    @for($c = 1; $c <= 6; $c++)
                    <th class="border border-gray-800 p-1">Col {{ $c }}</th>
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
                    <td class="border border-gray-800 p-1 font-bold">{{ $r }}</td>
                    @for($c = 1; $c <= 6; $c++)
                        @php
                            $val = $grid[$r][$c] ?? 0;
                            $colTotals[$c] += floatval($val);
                        @endphp
                    <td class="border border-gray-800 p-1">{{ floatval($val) > 0 ? floatval($val) : '' }}</td>
                    @endfor
                </tr>
                @endfor
            </tbody>
            <tfoot>
                <tr class="font-bold">
                    <td class="border border-gray-800 p-1">Total</td>
                    @for($c = 1; $c <= 6; $c++)
                    <td class="border border-gray-800 p-1">{{ $colTotals[$c] > 0 ? $colTotals[$c] : '' }}</td>
                    @endfor
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-4 flex justify-between items-center text-lg font-bold border-2 border-gray-800 p-2">
        <div>TOTAL PIECES: {{ $challan->total_pieces }}</div>
        <div>GRAND TOTAL METERS: {{ $challan->total_meters }}</div>
    </div>

    <div class="flex justify-between mt-12 pr-4 pl-4 items-end">
        <div>
            <p class="text-xs font-semibold uppercase">* No Dyeing Guarantee *</p>
            <p class="text-xs">Goods once sold will not be taken back.</p>
        </div>
        <div class="text-center font-bold">
            <p>For Gurudev Textiles</p>
            <div class="h-16"></div>
            <p>Authorised Signatory</p>
        </div>
    </div>
</body>
</html>
