@extends('layouts.app')
@section('title', 'Bills')

@section('actions')
    <a href="{{ route('owner.bills.create') }}" class="btn-primary text-xs">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Bill
    </a>
@endsection

@section('content')
<div class="space-y-4">
    {{-- Filters --}}
    <form method="GET" class="card flex flex-wrap items-end gap-3 py-3 px-4">
        <div class="flex-1 min-w-[150px]">
            <label class="form-label text-xs">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Bill number..." class="form-input text-sm">
        </div>
        <div class="min-w-[140px]">
            <label class="form-label text-xs">Customer</label>
            <select name="customer_id" class="form-select text-sm">
                <option value="">All</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label text-xs">From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input text-sm">
        </div>
        <div>
            <label class="form-label text-xs">To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input text-sm">
        </div>
        <button type="submit" class="btn-secondary text-xs">Filter</button>
        @if(request()->hasAny(['search','customer_id','date_from','date_to']))
            <a href="{{ route('owner.bills.index') }}" class="text-xs text-slate-500 hover:text-slate-700">Clear</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-slate-100 bg-slate-50/50"><tr>
                    <th class="table-th">Bill #</th>
                    <th class="table-th hidden sm:table-cell">Customer</th>
                    <th class="table-th">Date</th>
                    <th class="table-th text-right">Total</th>
                    <th class="table-th">Status</th>
                    <th class="table-th text-right">Actions</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($bills as $bill)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="table-td font-semibold text-slate-900">{{ $bill->bill_number }}</td>
                            <td class="table-td hidden sm:table-cell text-sm">{{ $bill->customer?->name ?? '—' }}</td>
                            <td class="table-td text-sm">{{ $bill->bill_date?->format('d M Y') }}</td>
                            <td class="table-td text-right font-bold">₹{{ number_format($bill->total, 2) }}</td>
                            <td class="table-td">
                                @if($bill->status === 'paid') <span class="badge-green">Paid</span>
                                @elseif($bill->status === 'final') <span class="badge-blue">Final</span>
                                @elseif($bill->status === 'draft') <span class="badge-amber">Draft</span>
                                @else <span class="badge-red">{{ ucfirst($bill->status) }}</span>
                                @endif
                            </td>
                            <td class="table-td text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('owner.bills.show', $bill) }}" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600" title="View">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('owner.bills.duplicate', $bill) }}" class="inline">@csrf
                                        <button type="submit" class="rounded-lg p-2 text-slate-400 hover:bg-blue-50 hover:text-blue-600" title="Duplicate"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-sm text-slate-400">No bills found. <a href="{{ route('owner.bills.create') }}" class="text-teal-700 font-semibold hover:underline">Create your first bill →</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bills->hasPages())<div class="border-t border-slate-100 px-4 py-3">{{ $bills->links() }}</div>@endif
    </div>
</div>
@endsection
