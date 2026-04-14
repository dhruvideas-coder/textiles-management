@extends('layouts.app')
@section('title', 'Edit Shop')

@section('content')
<form method="POST" action="{{ route('admin.shops.update', $shop) }}" class="mx-auto max-w-3xl space-y-6">
    @csrf @method('PUT')

    <div class="card">
        <h2 class="mb-5 text-lg font-bold text-slate-900">Shop Details</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="name" class="form-label">Shop Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $shop->name) }}" class="form-input" required>
            </div>
            <div>
                <label for="slug" class="form-label">Slug <span class="text-red-500">*</span></label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $shop->slug) }}" class="form-input" required>
            </div>
            <div>
                <label for="code" class="form-label">Shop Code</label>
                <input type="text" name="code" id="code" value="{{ old('code', $shop->code) }}" class="form-input">
            </div>
            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $shop->email) }}" class="form-input">
            </div>
            <div>
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $shop->phone) }}" class="form-input">
            </div>
            <div>
                <label for="gstin" class="form-label">GSTIN</label>
                <input type="text" name="gstin" id="gstin" value="{{ old('gstin', $shop->gstin) }}" class="form-input">
            </div>
            <div class="sm:col-span-2">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" rows="2" class="form-textarea">{{ old('address', $shop->address) }}</textarea>
            </div>
            <div>
                <label for="city" class="form-label">City</label>
                <input type="text" name="city" id="city" value="{{ old('city', $shop->city) }}" class="form-input">
            </div>
            <div>
                <label for="state" class="form-label">State</label>
                <input type="text" name="state" id="state" value="{{ old('state', $shop->state) }}" class="form-input">
            </div>
            <div>
                <label for="pincode" class="form-label">Pincode</label>
                <input type="text" name="pincode" id="pincode" value="{{ old('pincode', $shop->pincode) }}" class="form-input">
            </div>
        </div>
    </div>

    <div class="card">
        <h2 class="mb-5 text-lg font-bold text-slate-900">Appearance</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="theme_color" class="form-label">Theme Color</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="theme_color" id="theme_color" value="{{ old('theme_color', $shop->theme_color ?? '#0f766e') }}" class="h-10 w-14 cursor-pointer rounded-lg border border-slate-200">
                    <span class="text-sm text-slate-500">{{ $shop->theme_color ?? '#0f766e' }}</span>
                </div>
            </div>
            <div>
                <label for="footer_text" class="form-label">Invoice Footer Text</label>
                <input type="text" name="footer_text" id="footer_text" value="{{ old('footer_text', $shop->footer_text) }}" class="form-input" placeholder="Thank you for your business!">
            </div>
        </div>
    </div>

    <div class="card">
        <h2 class="mb-5 text-lg font-bold text-slate-900">Status</h2>
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $shop->is_active) ? 'checked' : '' }} class="h-5 w-5 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
            <span class="text-sm font-medium text-slate-700">Shop is active</span>
        </label>
        <p class="mt-1 ml-8 text-xs text-slate-400">Deactivating will also disable the owner's access.</p>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Update Shop</button>
        <a href="{{ route('admin.shops.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
@endsection
