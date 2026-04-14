@extends('layouts.app')
@section('title', 'Edit Staff: ' . $staff->name)

@section('content')
<form method="POST" action="{{ route('owner.staff.update', $staff) }}" class="mx-auto max-w-xl space-y-6">
    @csrf @method('PUT')

    <div class="card space-y-4">
        <div>
            <label class="form-label">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $staff->name) }}" class="form-input" required>
        </div>
        <div>
            <label class="form-label">Email Address <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email', $staff->email) }}" class="form-input" required>
        </div>
        <div class="pt-4">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $staff->is_active) ? 'checked' : '' }} class="h-5 w-5 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                <span class="text-sm font-medium text-slate-700">Account is active</span>
            </label>
            <p class="mt-1 ml-8 text-xs text-slate-500">Uncheck to revoke login access.</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Update Staff Member</button>
        <a href="{{ route('owner.staff.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>

<form method="POST" action="{{ route('owner.staff.destroy', $staff) }}" onsubmit="return confirm('Delete this staff member permanently?')" class="mx-auto max-w-xl mt-6 text-right">
    @csrf @method('DELETE')
    <button type="submit" class="text-xs text-red-600 hover:underline">Delete Account</button>
</form>
@endsection
