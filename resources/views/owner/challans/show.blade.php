@extends('layouts.app')
@section('title', 'Challan ' . $challan->challan_number)

@section('actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('owner.challans.pdf', $challan) }}" class="btn-primary text-xs">PDF</a>
        <form method="POST" action="{{ route('owner.challans.duplicate', $challan) }}" class="inline">@csrf
            <button type="submit" class="btn-secondary text-xs">Duplicate</button>
        </form>
    </div>
@endsection

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div class="card">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">{{ $challan->challan_number }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $challan->challan_date?->format('d M Y') }}</p>
                <div class="mt-4 text-sm text-slate-700">
                    <p><strong>Party:</strong> {{ $challan->party_name }}</p>
                    <p><strong>Broker:</strong> {{ $challan->broker_name ?? '—' }}</p>
                    @if($challan->customer) <p><strong>Customer:</strong> {{ $challan->customer->name }}</p> @endif
                    @if($challan->remarks) <p><strong>Remarks:</strong> {{ $challan->remarks }}</p> @endif
                </div>
            </div>
            <div class="text-right">
                @if($challan->status === 'final') <span class="badge-blue text-sm px-4 py-1">Final</span>
                @elseif($challan->status === 'draft') <span class="badge-amber text-sm px-4 py-1">Draft</span>
                @else <span class="badge-slate text-sm px-4 py-1">{{ ucfirst($challan->status) }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-slate-100 bg-slate-50/50"><tr>
                    <th class="table-th">#</th><th class="table-th">Product</th><th class="table-th text-right">Pcs</th><th class="table-th text-right">Meters</th><th class="table-th text-right">Weight</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($challan->items as $i => $item)
                        <tr>
                            <td class="table-td text-slate-400">{{ $i + 1 }}</td>
                            <td class="table-td font-medium">{{ $item->product_name }}</td>
                            <td class="table-td text-right">{{ $item->pieces ?: '—' }}</td>
                            <td class="table-td text-right">{{ $item->meters ?: '—' }}</td>
                            <td class="table-td text-right">{{ $item->weight ?: '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if(!auth()->user()->hasRole('staff'))
        <form method="POST" action="{{ route('owner.challans.destroy', $challan) }}" onsubmit="return confirm('Delete this challan permanently?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger text-xs">Delete Challan</button>
        </form>
    @endif
</div>
@endsection
