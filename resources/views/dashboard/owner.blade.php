@extends('layouts.app')
@section('title', 'Dashboard')

@section('actions')
    <a href="{{ route('owner.bills.create') }}" class="btn-primary text-xs">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Bill
    </a>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">₹{{ number_format($analytics['totalRevenue'], 0) }}</p>
                    <p class="text-xs font-medium text-slate-500">Total Revenue</p>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $analytics['topCustomers']->count() }}</p>
                    <p class="text-xs font-medium text-slate-500">Top Customers</p>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-purple-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $analytics['mostSoldProducts']->count() }}</p>
                    <p class="text-xs font-medium text-slate-500">Top Products</p>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3">
                @php $lowStockCount = $analytics['stockOverview']->filter(fn($i) => (float)$i->current_stock_meters <= (float)$i->low_stock_threshold)->count(); @endphp
                <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $lowStockCount > 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $lowStockCount }}</p>
                    <p class="text-xs font-medium text-slate-500">Low Stock Items</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="card">
            <h2 class="mb-4 font-bold text-slate-900">Monthly Sales</h2>
            <div id="chartMonthlySales"></div>
        </div>
        <div class="card">
            <h2 class="mb-4 font-bold text-slate-900">Daily Sales (Last 30 days)</h2>
            <div id="chartDailySales"></div>
        </div>
    </div>

    {{-- Top Customers & Products --}}
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="card">
            <h2 class="mb-4 font-bold text-slate-900">Top Customers</h2>
            <div class="space-y-3">
                @forelse($analytics['topCustomers'] as $customer)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-100 text-xs font-bold text-teal-700">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                            <span class="text-sm font-medium text-slate-700">{{ $customer->name }}</span>
                        </div>
                        <span class="font-bold text-sm text-slate-900">₹{{ number_format($customer->revenue ?? 0, 0) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">No customers yet.</p>
                @endforelse
            </div>
        </div>
        <div class="card">
            <h2 class="mb-4 font-bold text-slate-900">Most Sold Products</h2>
            <div class="space-y-3">
                @forelse($analytics['mostSoldProducts'] as $product)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">{{ $product->description }}</span>
                        <span class="badge-blue">{{ number_format($product->sold_meters, 1) }} m</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">No products sold yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Stock Overview --}}
    @if($analytics['stockOverview']->count())
    <div class="card">
        <h2 class="mb-4 font-bold text-slate-900">Stock Overview</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="border-b border-slate-100">
                    <th class="table-th">Item</th>
                    <th class="table-th text-right">Stock (m)</th>
                    <th class="table-th text-right">Threshold</th>
                    <th class="table-th text-right">Status</th>
                </tr></thead>
                <tbody>
                    @foreach($analytics['stockOverview'] as $item)
                        <tr class="border-b border-slate-50">
                            <td class="table-td font-medium">{{ $item->name }}</td>
                            <td class="table-td text-right">{{ number_format($item->current_stock_meters, 1) }}</td>
                            <td class="table-td text-right">{{ number_format($item->low_stock_threshold, 1) }}</td>
                            <td class="table-td text-right">
                                @if((float)$item->current_stock_meters <= (float)$item->low_stock_threshold)
                                    <span class="badge-red">Low</span>
                                @else
                                    <span class="badge-green">OK</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthly = @json($analytics['monthlySales']);
    const daily = @json($analytics['dailySales']);

    new ApexCharts(document.querySelector('#chartMonthlySales'), {
        chart: { type: 'area', height: 280, toolbar: { show: false }, fontFamily: 'Inter' },
        series: [{ name: 'Sales', data: Object.values(monthly) }],
        xaxis: { categories: Object.keys(monthly), labels: { style: { fontSize: '11px' } } },
        colors: ['#0f766e'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
        stroke: { curve: 'smooth', width: 2.5 },
        dataLabels: { enabled: false },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        tooltip: { y: { formatter: v => '₹' + v.toLocaleString() } }
    }).render();

    new ApexCharts(document.querySelector('#chartDailySales'), {
        chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily: 'Inter' },
        series: [{ name: 'Sales', data: Object.values(daily) }],
        xaxis: { categories: Object.keys(daily).map(d => d.slice(5)), labels: { style: { fontSize: '10px' }, rotate: -45 } },
        colors: ['#0d9488'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        tooltip: { y: { formatter: v => '₹' + v.toLocaleString() } }
    }).render();
});
</script>
@endpush
@endsection
