@extends('layouts.app')
@section('title', 'Bill ' . $bill->bill_number)

@section('actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('owner.bills.pdf', $bill) }}" class="btn-primary text-xs">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            PDF
        </a>
        <a href="{{ route('owner.bills.thermal', $bill) }}" class="btn-secondary text-xs">🖨 Thermal</a>
        <a href="{{ $bill->whatsapp_share_link }}" target="_blank" class="btn-secondary text-xs text-green-600">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            WhatsApp
        </a>
        <form method="POST" action="{{ route('owner.bills.duplicate', $bill) }}" class="inline">@csrf
            <button type="submit" class="btn-secondary text-xs">Duplicate</button>
        </form>
    </div>
@endsection

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    {{-- Bill Header --}}
    <div class="card">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">{{ $bill->bill_number }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $bill->bill_date?->format('d M Y') }}</p>
            </div>
            <div class="text-right">
                @if($bill->status === 'paid') <span class="badge-green text-sm px-4 py-1">Paid</span>
                @elseif($bill->status === 'final') <span class="badge-blue text-sm px-4 py-1">Final</span>
                @elseif($bill->status === 'draft') <span class="badge-amber text-sm px-4 py-1">Draft</span>
                @else <span class="badge-red text-sm px-4 py-1">{{ ucfirst($bill->status) }}</span>
                @endif
            </div>
        </div>
        @if($bill->customer)
            <div class="mt-4 rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Customer</p>
                <p class="mt-1 font-bold text-slate-900">{{ $bill->customer->name }}</p>
                @if($bill->customer->phone)<p class="text-sm text-slate-600">📱 {{ $bill->customer->phone }}</p>@endif
                @if($bill->customer->gstin)<p class="text-sm text-slate-600">GSTIN: {{ $bill->customer->gstin }}</p>@endif
            </div>
        @endif
    </div>

    {{-- Items Table --}}
    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-slate-100 bg-slate-50/50"><tr>
                    <th class="table-th">#</th><th class="table-th">Description</th><th class="table-th text-right">Pcs</th><th class="table-th text-right">Meters</th><th class="table-th text-right">Rate</th><th class="table-th text-right">Amount</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($bill->items as $i => $item)
                        <tr>
                            <td class="table-td text-slate-400">{{ $i + 1 }}</td>
                            <td class="table-td font-medium">{{ $item->description }} @if($item->inventory)<span class="text-xs text-slate-400">({{ $item->inventory->name }})</span>@endif</td>
                            <td class="table-td text-right">{{ $item->pieces }}</td>
                            <td class="table-td text-right">{{ number_format($item->meters, 2) }}</td>
                            <td class="table-td text-right">₹{{ number_format($item->rate, 2) }}</td>
                            <td class="table-td text-right font-semibold">₹{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Totals --}}
    <div class="card">
        <div class="ml-auto max-w-xs space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-slate-500">Subtotal</span><span class="font-medium">₹{{ number_format($bill->subtotal, 2) }}</span></div>
            @if($bill->discount > 0)<div class="flex justify-between text-red-600"><span>Discount</span><span>-₹{{ number_format($bill->discount, 2) }}</span></div>@endif
            @if($bill->transport_charges > 0)<div class="flex justify-between"><span class="text-slate-500">Transport</span><span>+₹{{ number_format($bill->transport_charges, 2) }}</span></div>@endif
            @if($bill->cgst > 0)<div class="flex justify-between"><span class="text-slate-500">CGST</span><span>₹{{ number_format($bill->cgst, 2) }}</span></div>@endif
            @if($bill->sgst > 0)<div class="flex justify-between"><span class="text-slate-500">SGST</span><span>₹{{ number_format($bill->sgst, 2) }}</span></div>@endif
            @if($bill->igst > 0)<div class="flex justify-between"><span class="text-slate-500">IGST</span><span>₹{{ number_format($bill->igst, 2) }}</span></div>@endif
            @if($bill->round_off != 0)<div class="flex justify-between"><span class="text-slate-500">Round-off</span><span>₹{{ number_format($bill->round_off, 2) }}</span></div>@endif
            <div class="flex justify-between border-t border-slate-200 pt-2 text-base"><span class="font-bold text-slate-900">Total</span><span class="font-extrabold text-teal-700">₹{{ number_format($bill->total, 2) }}</span></div>
            @if($bill->paid_amount > 0)<div class="flex justify-between"><span class="text-slate-500">Paid</span><span class="text-emerald-600 font-medium">₹{{ number_format($bill->paid_amount, 2) }}</span></div>@endif
        </div>
    </div>

    @if($bill->notes)
        <div class="card">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Notes</p>
            <p class="mt-1 text-sm text-slate-600">{{ $bill->notes }}</p>
        </div>
    @endif

    {{-- Delete --}}
    @if(!auth()->user()->hasRole('staff'))
        <form method="POST" action="{{ route('owner.bills.destroy', $bill) }}" onsubmit="return confirm('Delete this bill permanently?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger text-xs">Delete Bill</button>
        </form>
    @endif
</div>
@endsection
