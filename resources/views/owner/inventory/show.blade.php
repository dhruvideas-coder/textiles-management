@extends('layouts.app')
@section('title', 'Item: ' . $inventory->name)

@section('actions')
    <a href="{{ route('owner.inventory.edit', $inventory) }}" class="btn-secondary text-xs">Edit</a>
@endsection

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div class="card">
        <div class="grid sm:grid-cols-2 gap-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">{{ $inventory->name }}</h2>
                <div class="mt-3 space-y-2 text-sm text-slate-600">
                    <p><strong>SKU:</strong> {{ $inventory->sku ?? '—' }}</p>
                    <p><strong>Design:</strong> {{ $inventory->design_number ?? '—' }}</p>
                    <p><strong>Status:</strong> 
                        @if((float)$inventory->current_stock_meters <= (float)$inventory->low_stock_threshold)
                            <span class="badge-red">Low Stock</span>
                        @else
                            <span class="badge-green">OK</span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Current Stock</p>
                    <p class="text-2xl font-bold {{ (float)$inventory->current_stock_meters <= (float)$inventory->low_stock_threshold ? 'text-red-600' : 'text-teal-700' }}">{{ (float)$inventory->current_stock_meters }}m</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Selling Rate</p>
                    <p class="text-2xl font-bold text-slate-900">₹{{ number_format($inventory->rate, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(!auth()->user()->hasRole('staff'))
        <form method="POST" action="{{ route('owner.inventory.destroy', $inventory) }}" onsubmit="return confirm('Delete this item?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger text-xs">Delete Item</button>
        </form>
    @endif
</div>
@endsection
