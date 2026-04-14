@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-100 text-teal-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $overview['activeShops'] }}</p>
                    <p class="text-xs font-medium text-slate-500">Active Shops</p>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">₹{{ number_format($overview['platformRevenue'], 0) }}</p>
                    <p class="text-xs font-medium text-slate-500">Platform Revenue</p>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">{{ number_format($overview['totalBills']) }}</p>
                    <p class="text-xs font-medium text-slate-500">Total Bills</p>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">{{ number_format($overview['totalChallans']) }}</p>
                    <p class="text-xs font-medium text-slate-500">Total Challans</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card">
        <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-500">Quick Actions</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.shops.create') }}" class="btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New Shop
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">Manage Users</a>
            <a href="{{ route('admin.subscriptions.index') }}" class="btn-secondary">Manage Plans</a>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Recent Bills --}}
        <div class="card">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-bold text-slate-900">Recent Bills</h2>
                <a href="{{ route('admin.analytics.index') }}" class="text-xs font-semibold text-teal-700 hover:text-teal-800">View all →</a>
            </div>
            <div class="space-y-2">
                @forelse($latestBills as $bill)
                    <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                        <div>
                            <p class="font-semibold text-sm text-slate-900">{{ $bill->bill_number }}</p>
                            <p class="text-xs text-slate-500">{{ $bill->bill_date?->format('d M Y') }}</p>
                        </div>
                        <p class="font-bold text-sm text-slate-900">₹{{ number_format($bill->total, 2) }}</p>
                    </div>
                @empty
                    <div class="py-8 text-center text-sm text-slate-400">
                        <svg class="mx-auto mb-2 h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        No bills yet
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Challans --}}
        <div class="card">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-bold text-slate-900">Recent Challans</h2>
            </div>
            <div class="space-y-2">
                @forelse($latestChallans as $challan)
                    <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                        <div>
                            <p class="font-semibold text-sm text-slate-900">{{ $challan->challan_number }}</p>
                            <p class="text-xs text-slate-500">{{ $challan->party_name ?? '—' }}</p>
                        </div>
                        <p class="text-sm text-slate-500">{{ $challan->challan_date?->format('d M Y') }}</p>
                    </div>
                @empty
                    <div class="py-8 text-center text-sm text-slate-400">
                        <svg class="mx-auto mb-2 h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        No challans yet
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
