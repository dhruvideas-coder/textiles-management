@extends('layouts.app')
@section('title', 'Add Inventory Item')

@section('content')
<form method="POST" action="{{ route('owner.inventory.store') }}" class="mx-auto max-w-2xl space-y-6">
    @csrf

    <div class="card">
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="form-label">Item Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">SKU</label>
                <input type="text" name="sku" value="{{ old('sku') }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Design Number</label>
                <input type="text" name="design_number" value="{{ old('design_number') }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Selling Rate (₹) <span class="text-red-500">*</span></label>
                <input type="number" name="rate" value="{{ old('rate', '0.00') }}" class="form-input" step="0.01" min="0" required>
            </div>
            <div>
                <label class="form-label">Purchase Rate (₹)</label>
                <input type="number" name="purchase_rate" value="{{ old('purchase_rate', '0.00') }}" class="form-input" step="0.01" min="0">
            </div>
            <div>
                <label class="form-label">Initial Stock (Meters) <span class="text-red-500">*</span></label>
                <input type="number" name="current_stock_meters" value="{{ old('current_stock_meters', '0') }}" class="form-input" step="0.01" required>
            </div>
            <div>
                <label class="form-label">Low Stock Threshold (Meters)</label>
                <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', '10') }}" class="form-input" step="0.01">
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Save Item</button>
        <a href="{{ route('owner.inventory.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
@endsection
