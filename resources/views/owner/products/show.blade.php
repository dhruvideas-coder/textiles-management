@extends('layouts.app')
@section('title', 'Product: ' . $product->name)

@section('actions')
    <a href="{{ route('owner.products.edit', $product) }}" class="btn-secondary text-xs">Edit</a>
@endsection

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div class="card">
        <div class="grid sm:grid-cols-2 gap-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">{{ $product->name }}</h2>
                <div class="mt-3 space-y-2 text-sm text-slate-600">
                    <p><strong>SKU:</strong> {{ $product->sku ?? '—' }}</p>
                    <p><strong>Design Number:</strong> {{ $product->design_number ?? '—' }}</p>
                    <p><strong>Status:</strong> 
                        @if($product->isLowStock())
                            <span class="badge-red">Low Stock</span>
                        @else
                            <span class="badge-green">In Stock</span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Current Stock</p>
                    <p class="text-2xl font-bold {{ $product->isLowStock() ? 'text-red-600' : 'text-teal-700' }}">{{ (float)$product->current_stock_meters }}m</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Selling Rate</p>
                    <p class="text-2xl font-bold text-slate-900">₹{{ number_format($product->rate, 2) }}</p>
                </div>
            </div>
        </div>
        @if($product->description)
            <div class="mt-6 border-t border-slate-100 pt-4">
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Description</p>
                <p class="text-slate-600">{{ $product->description }}</p>
            </div>
        @endif
    </div>

    <div class="grid sm:grid-cols-2 gap-6">
         <div class="card">
            <h3 class="font-bold text-slate-900 mb-4">Pricing Details</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Purchase Rate:</span>
                    <span class="font-semibold text-slate-900">₹{{ number_format($product->purchase_rate, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Selling Rate:</span>
                    <span class="font-semibold text-slate-900">₹{{ number_format($product->rate, 2) }}</span>
                </div>
                <div class="flex justify-between border-t border-slate-50 pt-2 text-teal-700 font-bold">
                    <span>Margin:</span>
                    <span>₹{{ number_format($product->rate - $product->purchase_rate, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="card">
            <h3 class="font-bold text-slate-900 mb-4">Stock Information</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Alert Threshold:</span>
                    <span class="font-semibold text-slate-900">{{ number_format($product->low_stock_threshold, 2) }}m</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Unit:</span>
                    <span class="font-semibold text-slate-900">{{ $product->unit ?? 'meters' }}</span>
                </div>
            </div>
        </div>
    </div>

    @if(!auth()->user()->hasRole('staff'))
        <form method="POST" action="{{ route('owner.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger text-xs">Delete Product</button>
        </form>
    @endif
</div>
@endsection
