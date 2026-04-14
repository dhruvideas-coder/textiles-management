@extends('layouts.app')
@section('title', 'Users')

@section('content')
<div class="card overflow-hidden p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="border-b border-slate-100 bg-slate-50/50">
                <tr>
                    <th class="table-th">User</th>
                    <th class="table-th hidden sm:table-cell">Shop</th>
                    <th class="table-th">Role</th>
                    <th class="table-th">Status</th>
                    <th class="table-th hidden md:table-cell">Last Login</th>
                    <th class="table-th text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($users as $u)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="table-td">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 text-xs font-bold text-white">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                                <div>
                                    <p class="font-semibold text-sm text-slate-900">{{ $u->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="table-td hidden sm:table-cell text-sm text-slate-600">{{ $u->shop?->name ?? '—' }}</td>
                        <td class="table-td">
                            @php $role = $u->roles->pluck('name')->first() ?? '—'; @endphp
                            @if($role === 'super_admin') <span class="badge-amber">Super Admin</span>
                            @elseif($role === 'owner') <span class="badge-blue">Owner</span>
                            @else <span class="badge-slate">{{ ucfirst($role) }}</span>
                            @endif
                        </td>
                        <td class="table-td">
                            @if($u->is_active) <span class="badge-green">Active</span>
                            @else <span class="badge-red">Inactive</span>
                            @endif
                        </td>
                        <td class="table-td hidden md:table-cell text-sm text-slate-500">{{ $u->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                        <td class="table-td text-right">
                            <a href="{{ route('admin.users.edit', $u) }}" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600" title="Edit">
                                <svg class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-sm text-slate-400">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="border-t border-slate-100 px-4 py-3">{{ $users->links() }}</div>
    @endif
</div>
@endsection
