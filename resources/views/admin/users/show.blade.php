@extends('layouts.app')
@section('title', $user->name)

@section('actions')
    <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary text-xs">Edit User</a>
@endsection

@section('content')
<div class="mx-auto max-w-2xl space-y-6">

    {{-- User Info --}}
    <div class="card">
        <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 text-2xl font-bold text-white">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="text-xl font-bold text-slate-900">{{ $user->name }}</h2>
                    @php $role = $user->roles->pluck('name')->first() ?? null; @endphp
                    @if($role === 'super_admin') <span class="badge-amber">Super Admin</span>
                    @elseif($role === 'owner') <span class="badge-blue">Owner</span>
                    @elseif($role) <span class="badge-slate">{{ ucfirst($role) }}</span>
                    @endif
                    @if($user->is_active) <span class="badge-green">Active</span>
                    @else <span class="badge-red">Inactive</span>
                    @endif
                </div>
                <p class="mt-1 text-sm text-slate-500">{{ $user->email }}</p>
                <p class="mt-1 text-xs text-slate-400">
                    Last login: {{ $user->last_login_at?->diffForHumans() ?? 'Never' }}
                    &nbsp;·&nbsp; Joined: {{ $user->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Google Account --}}
    <div class="card">
        <h3 class="mb-3 font-bold text-slate-900">Google Account</h3>
        @if($user->google_id)
            <p class="text-sm text-slate-600">
                <span class="font-medium">Google ID:</span>
                <span class="font-mono text-xs text-slate-500">{{ $user->google_id }}</span>
            </p>
            <p class="mt-1 text-xs text-emerald-600">Google account linked — user can sign in.</p>
        @else
            <p class="text-sm text-slate-400">No Google account linked yet. The user must sign in with Google at least once.</p>
        @endif
    </div>

    {{-- Shop Info --}}
    <div class="card">
        <h3 class="mb-3 font-bold text-slate-900">Shop</h3>
        @if($user->shop)
            <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                <div>
                    <p class="font-semibold text-slate-900">{{ $user->shop->name }}</p>
                    <p class="text-xs text-slate-500">{{ $user->shop->slug }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if($user->shop->is_active)
                        <span class="badge-green">Active</span>
                    @else
                        <span class="badge-red">Inactive</span>
                    @endif
                    <a href="{{ route('admin.shops.show', $user->shop) }}" class="btn-secondary text-xs">View Shop</a>
                </div>
            </div>
        @else
            <p class="text-sm text-slate-400">Not assigned to any shop.</p>
        @endif
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3">
        @if($user->shop && $role !== 'super_admin')
            <form method="POST" action="{{ route('admin.impersonate.start', $user) }}">
                @csrf
                <button type="submit" class="btn-amber text-xs">Login as this User</button>
            </form>
        @endif
        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user permanently?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger text-xs">Delete User</button>
        </form>
    </div>

</div>
@endsection
