@extends('layouts.app')
@section('title', 'Customers')

@section('actions')
    <a href="{{ route('owner.customers.create') }}" class="btn-primary text-xs">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Customer
    </a>
@endsection

@section('content')
<div class="space-y-4">
    <form method="GET" class="card flex flex-wrap items-end gap-3 py-3 px-4">
        <div class="flex-1 min-w-[180px]">
            <label class="form-label text-xs">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, phone, city..." class="form-input text-sm">
        </div>
        <button type="submit" class="btn-secondary text-xs">Search</button>
        @if(request('search'))<a href="{{ route('owner.customers.index') }}" class="text-xs text-slate-500 hover:text-slate-700">Clear</a>@endif
    </form>

    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-slate-100 bg-slate-50/50"><tr>
                    <th class="table-th">Customer</th>
                    <th class="table-th hidden sm:table-cell">Contact</th>
                    <th class="table-th hidden md:table-cell">GSTIN</th>
                    <th class="table-th">Stats</th>
                    <th class="table-th text-right">Actions</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($customers as $c)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="table-td">
                                <div class="font-semibold text-slate-900">{{ $c->name }}</div>
                                <div class="text-xs text-slate-500">{{ $c->city ?? 'Unknown city' }}</div>
                            </td>
                            <td class="table-td hidden sm:table-cell text-sm">
                                @if($c->phone)<span class="block">📱 {{ $c->phone }}</span>@endif
                                @if($c->email)<span class="block">📧 {{ $c->email }}</span>@endif
                            </td>
                            <td class="table-td hidden md:table-cell text-sm text-slate-600">{{ $c->gstin ?? '—' }}</td>
                            <td class="table-td">
                                <div class="text-xs">
                                    <span class="font-medium text-slate-900">{{ $c->bills_count ?? 0 }} bills</span>
                                    @if(isset($c->bills_sum_total))
                                        <br><span class="text-teal-700 font-semibold">₹{{ number_format($c->bills_sum_total, 2) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="table-td text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('owner.customers.show', $c) }}" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                    <a href="{{ route('owner.customers.edit', $c) }}" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-sm text-slate-400">No customers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())<div class="border-t border-slate-100 px-4 py-3">{{ $customers->links() }}</div>@endif
    </div>
</div>
@endsection
