@extends('layouts.app')
@section('title', 'Create Shop')

@section('content')
<form method="POST" action="{{ route('admin.shops.store') }}" class="mx-auto max-w-3xl space-y-6">
    @csrf

    {{-- Shop Details --}}
    <div class="card">
        <h2 class="mb-5 text-lg font-bold text-slate-900">Shop Details</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="name" class="form-label">Shop Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input" required>
            </div>
            <div>
                <label for="slug" class="form-label">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="form-input" placeholder="Auto-generated">
            </div>
            <div>
                <label for="code" class="form-label">Shop Code</label>
                <input type="text" name="code" id="code" value="{{ old('code') }}" class="form-input">
            </div>
            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-input">
            </div>
            <div>
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-input">
            </div>
            <div>
                <label for="gstin" class="form-label">GSTIN</label>
                <input type="text" name="gstin" id="gstin" value="{{ old('gstin') }}" class="form-input">
            </div>
            <div class="sm:col-span-2">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" rows="2" class="form-textarea">{{ old('address') }}</textarea>
            </div>
            <div>
                <label for="city" class="form-label">City</label>
                <input type="text" name="city" id="city" value="{{ old('city') }}" class="form-input">
            </div>
            <div>
                <label for="state" class="form-label">State</label>
                <input type="text" name="state" id="state" value="{{ old('state') }}" class="form-input">
            </div>
            <div>
                <label for="pincode" class="form-label">Pincode</label>
                <input type="text" name="pincode" id="pincode" value="{{ old('pincode') }}" class="form-input">
            </div>
        </div>
    </div>

    {{-- Owner Details --}}
    <div class="card">
        <h2 class="mb-5 text-lg font-bold text-slate-900">Shop Owner</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="owner_name" class="form-label">Owner Name <span class="text-red-500">*</span></label>
                <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name') }}" class="form-input" required>
            </div>
            <div>
                <label for="owner_email" class="form-label">Owner Email (Google) <span class="text-red-500">*</span></label>
                <input type="email" name="owner_email" id="owner_email" value="{{ old('owner_email') }}" class="form-input" required>
                <p class="mt-1 text-xs text-slate-400">This email must match their Google account.</p>
            </div>
        </div>
    </div>

    {{-- Plan --}}
    <div class="card">
        <h2 class="mb-5 text-lg font-bold text-slate-900">Subscription Plan</h2>
        <div>
            <label for="plan_id" class="form-label">Select Plan <span class="text-red-500">*</span></label>
            <select name="plan_id" id="plan_id" class="form-select" required>
                <option value="">Choose plan...</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }} — ₹{{ number_format($plan->monthly_price, 0) }}/mo ({{ $plan->max_bills_per_month ?? '∞' }} bills, {{ $plan->max_staff_users ?? '∞' }} staff)
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Create Shop</button>
        <a href="{{ route('admin.shops.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
@endsection
