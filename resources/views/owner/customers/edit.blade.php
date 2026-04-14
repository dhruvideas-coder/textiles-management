@extends('layouts.app')
@section('title', 'Edit Customer: ' . $customer->name)

@section('content')
<form method="POST" action="{{ route('owner.customers.update', $customer) }}" class="mx-auto max-w-2xl space-y-6">
    @csrf @method('PUT')

    <div class="card">
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="form-label">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">GSTIN</label>
                <input type="text" name="gstin" value="{{ old('gstin', $customer->gstin) }}" class="form-input">
            </div>
            <div class="sm:col-span-2">
                <label class="form-label">Address</label>
                <textarea name="address" rows="2" class="form-textarea">{{ old('address', $customer->address) }}</textarea>
            </div>
            <div>
                <label class="form-label">City</label>
                <input type="text" name="city" value="{{ old('city', $customer->city) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">State</label>
                <input type="text" name="state" value="{{ old('state', $customer->state) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Pincode</label>
                <input type="text" name="pincode" value="{{ old('pincode', $customer->pincode) }}" class="form-input">
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Update Customer</button>
        <a href="{{ route('owner.customers.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
@endsection
