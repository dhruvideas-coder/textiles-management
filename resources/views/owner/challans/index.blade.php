@extends('layouts.app')
@section('title', 'Challans')

@section('actions')
    <a href="{{ route('owner.challans.create') }}" class="btn-primary text-xs">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Challan
    </a>
@endsection

@section('content')
<div class="space-y-4">
    <form method="GET" class="card flex flex-wrap items-end gap-3 py-3 px-4">
        <div class="flex-1 min-w-[180px]">
            <label class="form-label text-xs">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Challan number or party..." class="form-input text-sm">
        </div>
        <button type="submit" class="btn-secondary text-xs">Search</button>
        @if(request('search'))<a href="{{ route('owner.challans.index') }}" class="text-xs text-slate-500 hover:text-slate-700">Clear</a>@endif
    </form>

    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-slate-100 bg-slate-50/50"><tr>
                    <th class="table-th">Challan #</th>
                    <th class="table-th">Party</th>
                    <th class="table-th hidden sm:table-cell">Broker</th>
                    <th class="table-th">Date</th>
                    <th class="table-th">Status</th>
                    <th class="table-th text-right">Actions</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($challans as $challan)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="table-td font-semibold text-slate-900">{{ $challan->challan_number }}</td>
                            <td class="table-td text-sm">{{ $challan->party_name }}</td>
                            <td class="table-td hidden sm:table-cell text-sm text-slate-500">{{ $challan->broker_name ?? '—' }}</td>
                            <td class="table-td text-sm">{{ $challan->challan_date?->format('d M Y') }}</td>
                            <td class="table-td">
                                @if($challan->status === 'final') <span class="badge-blue">Final</span>
                                @elseif($challan->status === 'draft') <span class="badge-amber">Draft</span>
                                @else <span class="badge-red">{{ ucfirst($challan->status) }}</span>
                                @endif
                            </td>
                            <td class="table-td text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('owner.challans.show', $challan) }}" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600" title="View"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                    <a href="{{ route('owner.challans.pdf', $challan) }}" class="rounded-lg p-2 text-slate-400 hover:bg-teal-50 hover:text-teal-600" title="PDF"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-sm text-slate-400">No challans found. <a href="{{ route('owner.challans.create') }}" class="text-teal-700 font-semibold hover:underline">Create your first challan →</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($challans->hasPages())<div class="border-t border-slate-100 px-4 py-3">{{ $challans->links() }}</div>@endif
    </div>
</div>
@endsection
