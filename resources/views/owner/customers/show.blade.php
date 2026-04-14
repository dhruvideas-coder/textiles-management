@extends('layouts.app')
@section('title', 'Customer: ' . $customer->name)

@section('actions')
    <a href="{{ route('owner.customers.edit', $customer) }}" class="btn-secondary text-xs">Edit</a>
@endsection

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div class="card bg-slate-50 border-0 shadow-none ring-slate-200">
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">{{ $customer->name }}</h2>
                <div class="mt-3 space-y-1 text-sm text-slate-600">
                    @if($customer->phone)<p>📱 {{ $customer->phone }}</p>@endif
                    @if($customer->email)<p>📧 {{ $customer->email }}</p>@endif
                    @if($customer->gstin)<p>🏢 {{ $customer->gstin }}</p>@endif
                </div>
            </div>
            <div class="md:text-right">
                <p class="text-sm font-semibold text-slate-900">Total Spent</p>
                <p class="text-2xl font-bold text-teal-700">₹{{ number_format($bills->sum('total'), 2) }}</p>
                <p class="text-xs text-slate-500 mt-1">Across {{ $bills->total() }} bills</p>
            </div>
        </div>
    </div>

    <div class="card overflow-hidden p-0">
        <div class="border-b border-slate-100 px-5 py-4"><h2 class="font-bold text-slate-900">Recent Bills</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-slate-100 bg-slate-50/50"><tr>
                    <th class="table-th">Bill #</th><th class="table-th">Date</th><th class="table-th text-right">Total</th><th class="table-th">Status</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($bills as $bill)
                        <tr class="hover:bg-slate-50/50">
                            <td class="table-td font-semibold"><a href="{{ route('owner.bills.show', $bill) }}" class="text-teal-700 hover:underline">{{ $bill->bill_number }}</a></td>
                            <td class="table-td text-sm">{{ $bill->bill_date?->format('d M Y') }}</td>
                            <td class="table-td text-right font-medium">₹{{ number_format($bill->total, 2) }}</td>
                            <td class="table-td">
                                @if($bill->status === 'paid') <span class="badge-green">Paid</span>
                                @elseif($bill->status === 'final') <span class="badge-blue">Final</span>
                                @else <span class="badge-slate">{{ ucfirst($bill->status) }}</span>@endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-slate-400">No bills found for this customer.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bills->hasPages())<div class="border-t border-slate-100 px-4 py-3">{{ $bills->links() }}</div>@endif
    </div>

    @if(!auth()->user()->hasRole('staff'))
        <form method="POST" action="{{ route('owner.customers.destroy', $customer) }}" onsubmit="return confirm('Delete this customer? This does not delete their bills.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger text-xs">Delete Customer</button>
        </form>
    @endif
</div>
@endsection
