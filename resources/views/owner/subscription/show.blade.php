@extends('layouts.app')
@section('title', 'My Subscription')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <div class="card overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-2 bg-gradient-to-r from-teal-500 to-emerald-500"></div>
        <div class="mt-4 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-900">{{ $subscription->plan->name ?? 'Free Plan' }}</h2>
            <span class="badge-green text-sm px-3 py-1">{{ ucfirst($subscription->status) }}</span>
        </div>
        <p class="mt-2 text-3xl font-extrabold text-teal-700">₹{{ number_format($subscription->plan->monthly_price ?? 0) }} <span class="text-sm font-normal text-slate-500">/ month</span></p>
        
        <div class="mt-8 space-y-6">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-slate-700">Bills This Month</span>
                    <span class="text-slate-500">{{ $usage['bills'] }} / {{ $limits['maxBills'] ?? 'Unlimited' }}</span>
                </div>
                @if($limits['maxBills'])
                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full {{ $usage['bills'] >= $limits['maxBills'] ? 'bg-red-500' : 'bg-teal-500' }}" style="width: {{ min(($usage['bills'] / max($limits['maxBills'], 1)) * 100, 100) }}%"></div>
                    </div>
                @endif
            </div>
            
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-slate-700">Staff Accounts</span>
                    <span class="text-slate-500">{{ $usage['staff'] }} / {{ $limits['maxStaff'] ?? 'Unlimited' }}</span>
                </div>
                @if($limits['maxStaff'])
                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full {{ $usage['staff'] >= $limits['maxStaff'] ? 'bg-red-500' : 'bg-teal-500' }}" style="width: {{ min(($usage['staff'] / max($limits['maxStaff'], 1)) * 100, 100) }}%"></div>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 rounded-xl bg-slate-50 p-4 text-sm text-slate-600">
            <p><strong>Note:</strong> Subscription billing and upgrades are managed by the platform administrator. If you need to upgrade your limits or have billing inquiries, please contact support.</p>
        </div>
    </div>
</div>
@endsection
