@extends('layouts.app')
@section('title', 'Staff: ' . $staff->name)

@section('actions')
    <a href="{{ route('owner.staff.edit', $staff) }}" class="btn-secondary text-xs">Edit</a>
@endsection

@section('content')
<div class="mx-auto max-w-xl space-y-6">

    {{-- Staff Info --}}
    <div class="card">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 text-xl font-bold text-white">
                {{ strtoupper(substr($staff->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-lg font-bold text-slate-900">{{ $staff->name }}</h2>
                    <span class="badge-slate">Staff</span>
                    @if($staff->is_active)
                        <span class="badge-green">Active</span>
                    @else
                        <span class="badge-red">Inactive</span>
                    @endif
                </div>
                <p class="mt-1 text-sm text-slate-500">{{ $staff->email }}</p>
                <p class="mt-1 text-xs text-slate-400">
                    Last login: {{ $staff->last_login_at?->diffForHumans() ?? 'Never' }}
                    &nbsp;·&nbsp; Added: {{ $staff->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Google Account Status --}}
    <div class="card">
        <h3 class="mb-2 font-bold text-slate-900">Login Status</h3>
        @if($staff->google_id)
            <p class="text-sm text-emerald-700">Google account linked — staff can sign in with Google.</p>
        @else
            <p class="text-sm text-amber-700">Google account not yet linked. Staff must sign in with Google once to link their account.</p>
        @endif
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('owner.staff.edit', $staff) }}" class="btn-primary text-xs">Edit Staff</a>
        <a href="{{ route('owner.staff.index') }}" class="btn-secondary text-xs">Back to Staff</a>
    </div>

</div>
@endsection
