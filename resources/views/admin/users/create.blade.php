@extends('layouts.app')
@section('title', 'Create User')

@section('content')
<form method="POST" action="{{ route('admin.users.store') }}" class="mx-auto max-w-2xl space-y-6">
    @csrf

    <div class="card bg-blue-50 border-blue-200">
        <p class="text-sm text-blue-800">Users login via Google. Ensure the email address matches their Google account perfectly. Passwords are not used.</p>
    </div>

    <div class="card">
        <h2 class="mb-5 text-lg font-bold text-slate-900">User Details</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="form-label">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input" required>
            </div>
            <div>
                <label for="email" class="form-label">Email (Google Account) <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-input" required>
            </div>
            <div>
                <label for="role" class="form-label">Role <span class="text-red-500">*</span></label>
                <select name="role" id="role" class="form-select" required>
                    <option value="">— Select Role —</option>
                    <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Owner</option>
                    <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>
            <div>
                <label for="shop_id" class="form-label">Shop Assignment</label>
                <select name="shop_id" id="shop_id" class="form-select">
                    <option value="">— No Shop (For Super Admins) —</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-5 w-5 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                <span class="text-sm font-medium text-slate-700">User is active</span>
            </label>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Create User</button>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
@endsection
