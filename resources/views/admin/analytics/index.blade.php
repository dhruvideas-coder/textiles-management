@extends('layouts.app')
@section('title', 'Platform Analytics')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="stat-card"><div class="flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-100 text-teal-700"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg></div><div><p class="text-2xl font-extrabold text-slate-900">{{ $overview['activeShops'] }}</p><p class="text-xs font-medium text-slate-500">Active Shops</p></div></div></div>
        <div class="stat-card"><div class="flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v1"/></svg></div><div><p class="text-2xl font-extrabold text-slate-900">₹{{ number_format($overview['platformRevenue'], 0) }}</p><p class="text-xs font-medium text-slate-500">Platform Revenue</p></div></div></div>
        <div class="stat-card"><div class="flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/></svg></div><div><p class="text-2xl font-extrabold text-slate-900">{{ number_format($overview['totalBills']) }}</p><p class="text-xs font-medium text-slate-500">Total Bills</p></div></div></div>
        <div class="stat-card"><div class="flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-700"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div><div><p class="text-2xl font-extrabold text-slate-900">{{ number_format($overview['totalChallans']) }}</p><p class="text-xs font-medium text-slate-500">Total Challans</p></div></div></div>
    </div>

    {{-- All Bills --}}
    <div class="card overflow-hidden p-0">
        <div class="border-b border-slate-100 px-5 py-4"><h2 class="font-bold text-slate-900">All Bills Across Platform</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-slate-100 bg-slate-50/50"><tr>
                    <th class="table-th">Bill #</th><th class="table-th hidden sm:table-cell">Shop</th><th class="table-th">Date</th><th class="table-th text-right">Total</th><th class="table-th">Status</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($bills as $bill)
                        <tr class="hover:bg-slate-50/50"><td class="table-td font-semibold">{{ $bill->bill_number }}</td><td class="table-td hidden sm:table-cell text-sm">{{ $bill->shop?->name ?? '—' }}</td><td class="table-td text-sm">{{ $bill->bill_date?->format('d M Y') }}</td><td class="table-td text-right font-bold">₹{{ number_format($bill->total, 2) }}</td><td class="table-td"><span class="badge-{{ $bill->status === 'paid' ? 'green' : ($bill->status === 'cancelled' ? 'red' : 'slate') }}">{{ ucfirst($bill->status) }}</span></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($bills->hasPages())<div class="border-t border-slate-100 px-4 py-3">{{ $bills->links() }}</div>@endif
    </div>
</div>
@endsection
