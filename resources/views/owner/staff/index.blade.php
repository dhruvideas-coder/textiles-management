@extends('layouts.app')
@section('title', 'Staff Management')

@section('actions')
    <a href="{{ route('owner.staff.create') }}" class="btn-primary text-xs">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add Staff
    </a>
@endsection

@section('content')
<div class="card overflow-hidden p-0">
    <div class="border-b border-slate-100 bg-slate-50 px-5 py-4">
        <p class="text-sm text-slate-600">You are currently using <strong>{{ $staff->count() }}</strong> out of <strong>{{ $staffLimit ?? 'unlimited' }}</strong> staff slots.</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="border-b border-slate-100 bg-slate-50/50"><tr>
                <th class="table-th">Name</th>
                <th class="table-th">Email</th>
                <th class="table-th">Status</th>
                <th class="table-th text-right">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($staff as $u)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="table-td font-semibold text-slate-900">{{ $u->name }}</td>
                        <td class="table-td text-sm">{{ $u->email }}</td>
                        <td class="table-td">
                            @if($u->is_active) <span class="badge-green">Active</span>
                            @else <span class="badge-red">Inactive</span> @endif
                        </td>
                        <td class="table-td text-right">
                            <a href="{{ route('owner.staff.edit', $u) }}" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-12 text-center text-sm text-slate-400">No staff members found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
