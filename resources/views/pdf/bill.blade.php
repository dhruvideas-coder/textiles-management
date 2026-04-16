<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill - {{ $bill->bill_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Arial', sans-serif; }
    </style>
</head>
<body class="bg-blue-50 text-gray-900 p-8 text-sm max-w-4xl mx-auto border-2 border-blue-900 m-4">
    <div class="text-center pb-4 border-b-2 border-blue-900">
        <h1 class="text-4xl font-extrabold uppercase tracking-widest text-blue-900">Gurudev Textiles</h1>
        <p class="text-xs mt-1 font-semibold text-blue-800">TAX INVOICE</p>
        <p class="text-xs mt-1 text-gray-600">Surat, Gujarat | Ph: +91 99999 99999 | GSTIN: 24AAAAA0000A1Z5</p>
    </div>

    <div class="flex justify-between py-6 border-b-2 border-blue-900">
        <div class="w-1/2">
            <h2 class="font-bold text-gray-700">Billed To:</h2>
            <h3 class="font-bold text-lg text-blue-900 mt-1">{{ $bill->challan?->customer?->name }}</h3>
            <p>{{ $bill->challan?->customer?->address }}</p>
            <p><strong>GSTIN:</strong> {{ $bill->challan?->customer?->GSTIN }}</p>
        </div>
        <div class="w-1/2 text-right">
            <p><span class="font-bold">Invoice No:</span> <span class="text-red-600 font-bold">{{ $bill->bill_number }}</span></p>
            <p><span class="font-bold">Date:</span> {{ \Carbon\Carbon::parse($bill->created_at)->format('d/m/Y') }}</p>
            <p><span class="font-bold">Challan Ref:</span> {{ $bill->challan?->challan_number }}</p>
        </div>
    </div>

    <div class="mt-6">
        <table class="w-full border-collapse border border-blue-900 text-center">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="border border-blue-900 py-2">S.No</th>
                    <th class="border border-blue-900 py-2 text-left px-2">Description of Goods</th>
                    <th class="border border-blue-900 py-2">Total Meters</th>
                    <th class="border border-blue-900 py-2">Rate (₹)</th>
                    <th class="border border-blue-900 py-2">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="h-40 align-top">
                    <td class="border-r border-blue-900 py-2">1</td>
                    <td class="border-r border-blue-900 py-2 text-left px-2 font-bold">{{ $bill->challan?->product?->name }}</td>
                    <td class="border-r border-blue-900 py-2">{{ number_format($bill->total_meters, 2) }}</td>
                    <td class="border-r border-blue-900 py-2">{{ number_format($bill->rate, 2) }}</td>
                    <td class="py-2">{{ number_format($bill->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <table class="w-full border-collapse border-b border-l border-r border-blue-900 bg-white">
            <tr>
                <td rowspan="4" class="w-3/5 border-r border-blue-900 p-2 align-top italic text-xs">
                    * No Dyeing Guarantee * <br/>
                    1. Goods once sold will not be taken back.<br/>
                    2. Interest @ 18% p.a. will be charged if payment is not made within stipulated time.
                </td>
                <td class="w-1/5 py-1 px-2 font-bold text-right border-b border-blue-900">Taxable Value</td>
                <td class="w-1/5 py-1 px-2 text-right border-b border-blue-900">{{ number_format($bill->amount, 2) }}</td>
            </tr>
            <tr>
                <td class="py-1 px-2 font-bold text-right border-b border-blue-900">CGST (2.5%)</td>
                <td class="py-1 px-2 text-right border-b border-blue-900">{{ number_format($bill->cgst_amount, 2) }}</td>
            </tr>
            <tr>
                <td class="py-1 px-2 font-bold text-right border-b border-blue-900">SGST (2.5%)</td>
                <td class="py-1 px-2 text-right border-b border-blue-900">{{ number_format($bill->sgst_amount, 2) }}</td>
            </tr>
            <tr class="bg-blue-100">
                <td class="py-2 px-2 font-bold text-right text-lg border-blue-900 text-blue-900">Grand Total</td>
                <td class="py-2 px-2 text-right font-bold text-lg text-blue-900">₹ {{ number_format($bill->final_total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="mt-8 pt-4 flex justify-between pr-4 pl-4 items-end">
        <div class="font-bold text-xs uppercase overflow-hidden">
            Amount in words: <br>
            <span class="font-normal">{{ (new \NumberFormatter("en_IN", \NumberFormatter::SPELLOUT))->format($bill->final_total) }} rupees only</span>
        </div>
        <div class="text-center font-bold">
            <p>For Gurudev Textiles</p>
            <div class="h-16"></div>
            <p>Authorised Signatory</p>
        </div>
    </div>
</body>
</html>
