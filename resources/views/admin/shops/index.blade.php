@extends('layouts.app')
@section('title', 'Shops')

@section('actions')
    <a href="{{ route('admin.shops.create') }}" class="btn-primary text-xs">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Shop
    </a>
@endsection

@section('content')
<div class="card overflow-hidden p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="border-b border-slate-100 bg-slate-50/50">
                <tr>
                    <th class="table-th">Shop</th>
                    <th class="table-th hidden sm:table-cell">Owner</th>

                    <th class="table-th">Status</th>
                    <th class="table-th text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($shops as $shop)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="table-td">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $shop->name }}</p>
                                <p class="text-xs text-slate-500">{{ $shop->city ?? $shop->slug }}</p>
                            </div>
                        </td>
                        <td class="table-td hidden sm:table-cell">
                            <p class="text-sm">{{ $shop->owner?->name ?? '—' }}</p>
                            <p class="text-xs text-slate-400">{{ $shop->owner?->email }}</p>
                        </td>

                        <td class="table-td">
                            @if($shop->is_active)
                                <span class="badge-green">Active</span>
                            @else
                                <span class="badge-red">Inactive</span>
                            @endif
                        </td>
                        <td class="table-td text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.shops.show', $shop) }}" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600" title="View">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.shops.edit', $shop) }}" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600" title="Edit">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @if($shop->owner)
                                    <form method="POST" action="{{ route('admin.impersonate.start', $shop->owner) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="rounded-lg p-2 text-slate-400 hover:bg-amber-50 hover:text-amber-600" title="Login as Owner">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.shops.destroy', $shop) }}" onsubmit="return confirm('Delete this shop?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-lg p-2 text-slate-400 hover:bg-red-50 hover:text-red-600" title="Delete">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-sm text-slate-400">No shops created yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($shops->hasPages())
        <div class="border-t border-slate-100 px-4 py-3">{{ $shops->links() }}</div>
    @endif
</div>
@endsection
