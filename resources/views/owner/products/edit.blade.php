@extends('layouts.app')
@section('title', 'Edit Product: ' . $product->name)

@section('content')
<form method="POST" action="{{ route('owner.products.update', $product) }}" class="mx-auto max-w-2xl space-y-6">
    @csrf @method('PUT')

    <div class="card">
        <h2 class="mb-5 text-lg font-bold text-slate-900">Product Details</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="form-label">Product Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">SKU</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Design Number</label>
                <input type="text" name="design_number" value="{{ old('design_number', $product->design_number) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Selling Rate (₹) <span class="text-red-500">*</span></label>
                <input type="number" name="rate" value="{{ old('rate', $product->rate) }}" class="form-input" step="0.01" min="0" required>
            </div>
            <div>
                <label class="form-label">Purchase Rate (₹)</label>
                <input type="number" name="purchase_rate" value="{{ old('purchase_rate', $product->purchase_rate) }}" class="form-input" step="0.01" min="0">
            </div>
            <div>
                <label class="form-label">Current Stock (Meters) <span class="text-red-500">*</span></label>
                <input type="number" name="current_stock_meters" value="{{ old('current_stock_meters', $product->current_stock_meters) }}" class="form-input border-amber-300 focus:border-amber-500 bg-amber-50" step="0.01" required>
                <p class="text-xs text-amber-700 mt-1">Directly updating this overrides automatic bill deductions.</p>
            </div>
            <div>
                <label class="form-label">Low Stock Threshold (Meters)</label>
                <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" class="form-input" step="0.01">
            </div>
            <div class="sm:col-span-2">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-textarea">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Update Product</button>
        <a href="{{ route('owner.products.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
@endsection
