@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<form method="POST" action="{{ route('admin.users.update', $user) }}" class="mx-auto max-w-2xl space-y-6">
    @csrf @method('PUT')

    <div class="card">
        <h2 class="mb-5 text-lg font-bold text-slate-900">User Details</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="form-label">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-input" required>
            </div>
            <div>
                <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-input" required>
            </div>
            <div>
                <label for="role" class="form-label">Role <span class="text-red-500">*</span></label>
                <select name="role" id="role" class="form-select" required>
                    <option value="owner" {{ $user->hasRole('owner') ? 'selected' : '' }}>Owner</option>
                    <option value="staff" {{ $user->hasRole('staff') ? 'selected' : '' }}>Staff</option>
                    <option value="super_admin" {{ $user->hasRole('super_admin') ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>
            <div>
                <label for="shop_id" class="form-label">Shop</label>
                <select name="shop_id" id="shop_id" class="form-select">
                    <option value="">— No Shop —</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ old('shop_id', $user->shop_id) == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="h-5 w-5 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                <span class="text-sm font-medium text-slate-700">User is active</span>
            </label>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Update User</button>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
@endsection
