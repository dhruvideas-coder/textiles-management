@extends('layouts.app')
@section('title', 'Detailed Analytics')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="stat-card">
            <p class="text-xs font-semibold text-slate-500">Total Billed</p>
            <p class="text-2xl font-bold text-slate-900">₹{{ number_format($stats['totalRevenue'], 2) }}</p>
        </div>
        <div class="stat-card">
            <p class="text-xs font-semibold text-slate-500">Amount Paid</p>
            <p class="text-2xl font-bold text-emerald-600">₹{{ number_format($stats['receivedAmount'], 2) }}</p>
        </div>
        <div class="stat-card">
            <p class="text-xs font-semibold text-slate-500">Outstanding (Pending)</p>
            <p class="text-2xl font-bold text-amber-600">₹{{ number_format($stats['pendingAmount'], 2) }}</p>
        </div>
        <div class="stat-card">
            <p class="text-xs font-semibold text-slate-500">Avg Bill Value</p>
            @php $count = max(1, array_sum(array_column($stats['yearlySales'], 'count'))); @endphp
            <p class="text-2xl font-bold text-slate-900">₹{{ number_format($stats['totalRevenue'] / $count, 2) }}</p>
        </div>
    </div>

    <div class="card">
        <h2 class="mb-4 font-bold text-slate-900">Revenue by Month (Current Year)</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-slate-100 bg-slate-50/50"><tr>
                    <th class="table-th text-xs">Month</th><th class="table-th text-right text-xs">Bills</th><th class="table-th text-right text-xs">Revenue</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($stats['yearlySales'] as $month => $data)
                        <tr>
                            <td class="table-td text-sm font-medium">{{ Carbon\Carbon::create()->month((int)$month)->format('F') }}</td>
                            <td class="table-td text-right text-sm">{{ $data['count'] }}</td>
                            <td class="table-td text-right text-sm font-semibold">₹{{ number_format($data['total'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
