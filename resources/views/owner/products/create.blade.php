@extends('layouts.app')
@section('title', 'Add Product')

@section('content')
<form method="POST" action="{{ route('owner.products.store') }}" class="mx-auto max-w-2xl space-y-6">
    @csrf

    <div class="card">
        <h2 class="mb-5 text-lg font-bold text-slate-900">Product Details</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="form-label">Product Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input" required placeholder="e.g. Cotton Soft Fabric">
            </div>
            <div>
                <label class="form-label">SKU</label>
                <input type="text" name="sku" value="{{ old('sku') }}" class="form-input" placeholder="Stock Keeping Unit">
            </div>
            <div>
                <label class="form-label">Design Number</label>
                <input type="text" name="design_number" value="{{ old('design_number') }}" class="form-input" placeholder="e.g. D-101">
            </div>
            <div>
                <label class="form-label">HSN Code</label>
                <input type="text" name="hsn_code" value="{{ old('hsn_code') }}" class="form-input" placeholder="e.g. 5407">
            </div>
            <div>
                <label class="form-label">Selling Rate (₹) <span class="text-red-500">*</span></label>
                <input type="number" name="rate" value="{{ old('rate') }}" class="form-input" step="0.01" min="0" required>
            </div>
            <div>
                <label class="form-label">Purchase Rate (₹)</label>
                <input type="number" name="purchase_rate" value="{{ old('purchase_rate') }}" class="form-input" step="0.01" min="0">
            </div>
            <div>
                <label class="form-label">Opening Stock (Meters) <span class="text-red-500">*</span></label>
                <input type="number" name="current_stock_meters" value="{{ old('current_stock_meters', 0) }}" class="form-input" step="0.01" required>
            </div>
            <div>
                <label class="form-label">Low Stock Threshold (Meters)</label>
                <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 0) }}" class="form-input" step="0.01">
            </div>
            <div class="sm:col-span-2">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-textarea" placeholder="Optional details...">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Create Product</button>
        <a href="{{ route('owner.products.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
@endsection
