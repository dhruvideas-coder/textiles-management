@extends('layouts.app')
@section('title', $shop->name)

@section('actions')
    <a href="{{ route('admin.shops.edit', $shop) }}" class="btn-secondary text-xs">Edit Shop</a>
@endsection

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    {{-- Shop Info --}}
    <div class="card">
        <div class="flex items-start gap-4">
            @if($shop->logo_path)
                <img src="{{ asset('storage/' . $shop->logo_path) }}" class="h-16 w-16 rounded-xl object-cover" alt="Logo">
            @else
                <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-gradient-to-br from-teal-400 to-emerald-500 text-2xl font-bold text-white">{{ strtoupper(substr($shop->name, 0, 1)) }}</div>
            @endif
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-slate-900">{{ $shop->name }}</h2>
                    @if($shop->is_active)
                        <span class="badge-green">Active</span>
                    @else
                        <span class="badge-red">Inactive</span>
                    @endif
                </div>
                <p class="mt-1 text-sm text-slate-500">{{ $shop->slug }} · {{ $shop->code ?? 'No code' }}</p>
                <div class="mt-3 flex flex-wrap gap-4 text-sm text-slate-600">
                    @if($shop->email) <span>📧 {{ $shop->email }}</span> @endif
                    @if($shop->phone) <span>📱 {{ $shop->phone }}</span> @endif
                    @if($shop->gstin) <span>🏢 {{ $shop->gstin }}</span> @endif
                    @if($shop->city) <span>📍 {{ $shop->city }}, {{ $shop->state }}</span> @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Subscription --}}
    <div class="card">
        <h3 class="mb-3 font-bold text-slate-900">Subscription</h3>
        @if($shop->subscription && $shop->subscription->plan)
            <div class="flex items-center gap-4">
                <span class="badge-blue text-base px-4 py-1">{{ $shop->subscription->plan->name }}</span>
                <span class="text-sm text-slate-500">₹{{ number_format($shop->subscription->plan->monthly_price, 0) }}/month</span>
                <span class="badge-green">{{ ucfirst($shop->subscription->status) }}</span>
            </div>
            <div class="mt-3 flex gap-6 text-sm text-slate-500">
                <span>Bills: {{ $shop->subscription->plan->max_bills_per_month ?? '∞' }}/mo</span>
                <span>Staff: {{ $shop->subscription->plan->max_staff_users ?? '∞' }}</span>
            </div>
        @else
            <p class="text-sm text-slate-400">No active subscription</p>
        @endif
    </div>

    {{-- Owner --}}
    <div class="card">
        <h3 class="mb-3 font-bold text-slate-900">Owner</h3>
        @if($shop->owner)
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-teal-100 text-sm font-bold text-teal-700">{{ strtoupper(substr($shop->owner->name, 0, 1)) }}</div>
                <div>
                    <p class="font-semibold text-slate-900">{{ $shop->owner->name }}</p>
                    <p class="text-xs text-slate-500">{{ $shop->owner->email }}</p>
                </div>
                <form method="POST" action="{{ route('admin.impersonate.start', $shop->owner) }}" class="ml-auto">
                    @csrf
                    <button type="submit" class="btn-amber text-xs">Login as Owner</button>
                </form>
            </div>
        @else
            <p class="text-sm text-slate-400">No owner assigned</p>
        @endif
    </div>

    {{-- Staff/Users --}}
    <div class="card">
        <h3 class="mb-3 font-bold text-slate-900">Users ({{ $shop->users->count() }})</h3>
        <div class="space-y-3">
            @foreach($shop->users as $u)
                <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-xs font-bold text-slate-600">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $u->name }}</p>
                            <p class="text-xs text-slate-500">{{ $u->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="badge-slate">{{ $u->roles->pluck('name')->first() ?? '—' }}</span>
                        @if($u->is_active)
                            <span class="badge-green">Active</span>
                        @else
                            <span class="badge-red">Inactive</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
