@extends('layouts.app')
@section('title', 'Shop Settings')

@section('content')
<form method="POST" action="{{ route('owner.settings.update') }}" enctype="multipart/form-data" class="mx-auto max-w-2xl space-y-6">
    @csrf @method('PUT')

    <div class="card space-y-6">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Brand Identity</h2>
            <div class="mt-4 flex items-center gap-6">
                @if($shop->logo_path)
                    <img src="{{ asset('storage/' . $shop->logo_path) }}" class="h-20 w-20 rounded-xl object-cover ring-1 ring-slate-200" alt="Logo">
                @else
                    <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-slate-100 text-slate-400">No Logo</div>
                @endif
                <div>
                    <label class="form-label">Upload New Logo</label>
                    <input type="file" name="logo" class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-teal-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-teal-700 hover:file:bg-teal-100">
                    <p class="mt-1 text-xs text-slate-400">PNG, JPG, max 2MB. Recommendation: Square image.</p>
                </div>
            </div>
        </div>

        <div class="h-px bg-slate-100"></div>

        <div>
            <h2 class="mb-4 text-lg font-bold text-slate-900">Shop Information</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="form-label">Shop Name</label>
                    <input type="text" name="name" value="{{ old('name', $shop->name) }}" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $shop->email) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $shop->phone) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">GSTIN</label>
                    <input type="text" name="gstin" value="{{ old('gstin', $shop->gstin) }}" class="form-input">
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="address" rows="2" class="form-textarea">{{ old('address', $shop->address) }}</textarea>
                </div>
                <div>
                    <label class="form-label">City</label>
                    <input type="text" name="city" value="{{ old('city', $shop->city) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">State</label>
                    <input type="text" name="state" value="{{ old('state', $shop->state) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Pincode</label>
                    <input type="text" name="pincode" value="{{ old('pincode', $shop->pincode) }}" class="form-input">
                </div>
            </div>
        </div>

        <div class="h-px bg-slate-100"></div>

        <div>
            <h2 class="mb-4 text-lg font-bold text-slate-900">Invoice Settings</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="form-label">Theme Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="theme_color" value="{{ old('theme_color', $shop->theme_color ?? '#0f766e') }}" class="h-10 w-14 cursor-pointer rounded-lg border border-slate-200">
                    </div>
                </div>
                <div>
                    <label class="form-label">Footer Text</label>
                    <input type="text" name="footer_text" value="{{ old('footer_text', $shop->footer_text) }}" class="form-input" placeholder="Thank you!">
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Save Settings</button>
    </div>
</form>
@endsection
