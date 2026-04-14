@extends('layouts.app')
@section('title', 'Add Staff')

@section('content')
<form method="POST" action="{{ route('owner.staff.store') }}" class="mx-auto max-w-xl space-y-6">
    @csrf

    <div class="card bg-blue-50 border-blue-200">
        <p class="text-sm text-blue-800">Staff members will login using <strong>Continue with Google</strong> using the email address you provide here.</p>
    </div>

    <div class="card space-y-4">
        <div>
            <label class="form-label">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
        </div>
        <div>
            <label class="form-label">Email Address (Google Account) <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-input" required>
        </div>
        <div class="pt-4">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-5 w-5 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                <span class="text-sm font-medium text-slate-700">Account is active</span>
            </label>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Save Staff Member</button>
        <a href="{{ route('owner.staff.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
@endsection
