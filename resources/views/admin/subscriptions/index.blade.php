@extends('layouts.app')
@section('title', 'Subscriptions')

@section('content')
<div class="space-y-6">
    @foreach($shops as $shop)
        <div class="card">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h3 class="font-bold text-slate-900">{{ $shop->name }}</h3>
                    <p class="text-sm text-slate-500">Owner: {{ $shop->owner?->name ?? '—' }}</p>
                    @if($shop->subscription)
                        <div class="mt-2 flex items-center gap-3">
                            <span class="badge-blue">{{ $shop->subscription->plan?->name ?? 'Unknown' }}</span>
                            <span class="badge-green">{{ ucfirst($shop->subscription->status) }}</span>
                            <span class="text-xs text-slate-400">Started {{ $shop->subscription->started_at?->format('d M Y') }}</span>
                        </div>
                    @else
                        <p class="mt-2 text-sm text-slate-400">No subscription</p>
                    @endif
                </div>
                <form method="POST" action="{{ route('admin.subscriptions.update', $shop) }}" class="flex items-end gap-3">
                    @csrf @method('PUT')
                    <div>
                        <label class="form-label text-xs">Change Plan</label>
                        <select name="plan_id" class="form-select text-sm">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ $shop->subscription?->plan_id == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} (₹{{ number_format($plan->monthly_price, 0) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-primary text-xs">Update</button>
                </form>
            </div>
            {{-- Usage Stats --}}
            @if($shop->subscription?->plan)
                <div class="mt-4 grid grid-cols-2 gap-4 rounded-xl bg-slate-50 p-4 sm:grid-cols-3">
                    <div>
                        <p class="text-xs text-slate-500">Bills This Month</p>
                        @php $used = $shop->bills()->whereMonth('bill_date', now()->month)->whereYear('bill_date', now()->year)->count(); $limit = $shop->subscription->plan->max_bills_per_month; @endphp
                        <p class="text-lg font-bold text-slate-900">{{ $used }} <span class="text-sm font-normal text-slate-400">/ {{ $limit ?? '∞' }}</span></p>
                        @if($limit)
                            <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-slate-200"><div class="h-full rounded-full {{ $used >= $limit ? 'bg-red-500' : 'bg-teal-500' }}" style="width:{{ min(($used / max($limit,1)) * 100, 100) }}%"></div></div>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Staff Users</p>
                        @php $staffCount = $shop->users()->role('staff')->count(); $staffLimit = $shop->subscription->plan->max_staff_users; @endphp
                        <p class="text-lg font-bold text-slate-900">{{ $staffCount }} <span class="text-sm font-normal text-slate-400">/ {{ $staffLimit ?? '∞' }}</span></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Plan Price</p>
                        <p class="text-lg font-bold text-slate-900">₹{{ number_format($shop->subscription->plan->monthly_price, 0) }}<span class="text-sm font-normal text-slate-400">/mo</span></p>
                    </div>
                </div>
            @endif
        </div>
    @endforeach

    @if($shops->hasPages())
        <div>{{ $shops->links() }}</div>
    @endif
</div>
@endsection
