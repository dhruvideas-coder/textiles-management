<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'Textile SaaS') }}</title>
    <meta name="description" content="Textile Billing, Challan & Shop Management SaaS">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @stack('head')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased" x-data="{ sidebarOpen: false }">

    @php
        $user = auth()->user();
        $isAdmin = $user->isSuperAdmin();
        $theme = $currentShop->theme_color ?? '#0f766e';
    @endphp

    {{-- Mobile sidebar overlay --}}
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/50 md:hidden" @click="sidebarOpen = false"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-gradient-to-b from-slate-900 via-slate-900 to-slate-800 transition-transform duration-300 md:translate-x-0">

        {{-- Brand --}}
        <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5">
            @if(!$isAdmin && $currentShop && $currentShop->logo_path)
                <img src="{{ asset('storage/' . $currentShop->logo_path) }}" class="h-8 w-8 rounded-lg object-cover" alt="Logo">
            @else
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-teal-400 to-emerald-500 text-sm font-bold text-white">T</div>
            @endif
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-bold text-white">{{ $isAdmin ? 'Super Admin' : ($currentShop->name ?? 'Textile SaaS') }}</p>
                <p class="text-[11px] text-slate-400">{{ $isAdmin ? 'Platform Manager' : $user->getRoleNames()->first() }}</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            @if($isAdmin)
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'nav-link-active' : 'nav-link' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                    Dashboard
                </a>
                <p class="mt-5 mb-2 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-500">Management</p>
                <a href="{{ route('admin.shops.index') }}" class="{{ request()->routeIs('admin.shops.*') ? 'nav-link-active' : 'nav-link' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Shops
                </a>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'nav-link-active' : 'nav-link' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Users
                </a>
                <a href="{{ route('admin.subscriptions.index') }}" class="{{ request()->routeIs('admin.subscriptions.*') ? 'nav-link-active' : 'nav-link' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Subscriptions
                </a>
                <a href="{{ route('admin.analytics.index') }}" class="{{ request()->routeIs('admin.analytics.*') ? 'nav-link-active' : 'nav-link' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6m6 0H3m6 0h6m0 0v-3a2 2 0 012-2h2a2 2 0 012 2v3m-6 0h6"/></svg>
                    Analytics
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'nav-link-active' : 'nav-link' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                    Dashboard
                </a>
                <p class="mt-5 mb-2 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-500">Business</p>
                <a href="{{ route('owner.bills.index') }}" class="{{ request()->routeIs('owner.bills.*') ? 'nav-link-active' : 'nav-link' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/></svg>
                    Bills
                </a>
                <a href="{{ route('owner.challans.index') }}" class="{{ request()->routeIs('owner.challans.*') ? 'nav-link-active' : 'nav-link' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Challans
                </a>
                <a href="{{ route('owner.customers.index') }}" class="{{ request()->routeIs('owner.customers.*') ? 'nav-link-active' : 'nav-link' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Customers
                </a>
                <a href="{{ route('owner.inventory.index') }}" class="{{ request()->routeIs('owner.inventory.*') ? 'nav-link-active' : 'nav-link' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Inventory
                </a>
                <a href="{{ route('owner.analytics.index') }}" class="{{ request()->routeIs('owner.analytics.*') ? 'nav-link-active' : 'nav-link' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6m6 0H3m6 0h6m0 0v-3a2 2 0 012-2h2a2 2 0 012 2v3m-6 0h6"/></svg>
                    Analytics
                </a>
                @if($user->hasRole('owner'))
                    <p class="mt-5 mb-2 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-500">Settings</p>
                    <a href="{{ route('owner.staff.index') }}" class="{{ request()->routeIs('owner.staff.*') ? 'nav-link-active' : 'nav-link' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Staff
                    </a>
                    <a href="{{ route('owner.settings.edit') }}" class="{{ request()->routeIs('owner.settings.*') ? 'nav-link-active' : 'nav-link' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Shop Settings
                    </a>
                    <a href="{{ route('owner.subscription.show') }}" class="{{ request()->routeIs('owner.subscription.*') ? 'nav-link-active' : 'nav-link' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Subscription
                    </a>
                @endif
            @endif
        </nav>

        {{-- User foot --}}
        <div class="border-t border-white/10 p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 text-xs font-bold text-white">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-white">{{ $user->name }}</p>
                    <p class="truncate text-[11px] text-slate-400">{{ $user->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg p-2 text-slate-400 hover:bg-white/10 hover:text-white" title="Logout">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="md:ml-72">
        {{-- Top bar --}}
        <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white/80 px-4 backdrop-blur-xl md:px-6">
            <button @click="sidebarOpen = !sidebarOpen" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 md:hidden">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="text-lg font-bold text-slate-900 md:text-xl">@yield('title', 'Dashboard')</h1>
            <div class="flex items-center gap-2">
                @if(session('impersonator_id'))
                    <form method="POST" action="{{ route('admin.impersonate.stop') }}">
                        @csrf
                        <button class="btn-amber text-xs">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Stop Impersonation
                        </button>
                    </form>
                @endif
                @yield('actions')
            </div>
        </header>

        {{-- Page content --}}
        <main class="p-4 pb-24 md:p-6">
            @if (session('status'))
                <div class="mb-4 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" x-data="{ show: true }" x-show="show" x-transition>
                    <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="flex-1">{{ session('status') }}</span>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="space-y-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- Mobile bottom nav --}}
    <nav class="fixed bottom-0 left-0 right-0 z-30 border-t border-slate-200 bg-white/95 backdrop-blur-lg md:hidden">
        <div class="grid {{ $isAdmin ? 'grid-cols-4' : 'grid-cols-5' }} text-[10px]">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('dashboard') ? 'text-teal-700 font-semibold' : 'text-slate-500' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                Home
            </a>
            @if($isAdmin)
                <a href="{{ route('admin.shops.index') }}" class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('admin.shops.*') ? 'text-teal-700 font-semibold' : 'text-slate-500' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Shops
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('admin.users.*') ? 'text-teal-700 font-semibold' : 'text-slate-500' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
                    Users
                </a>
                <a href="{{ route('admin.analytics.index') }}" class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('admin.analytics.*') ? 'text-teal-700 font-semibold' : 'text-slate-500' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6m6 0H3m6 0h6m0 0v-3a2 2 0 012-2h2a2 2 0 012 2v3m-6 0h6"/></svg>
                    Stats
                </a>
            @else
                <a href="{{ route('owner.bills.index') }}" class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('owner.bills.*') ? 'text-teal-700 font-semibold' : 'text-slate-500' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/></svg>
                    Bills
                </a>
                <a href="{{ route('owner.challans.index') }}" class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('owner.challans.*') ? 'text-teal-700 font-semibold' : 'text-slate-500' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Challan
                </a>
                <a href="{{ route('owner.customers.index') }}" class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('owner.customers.*') ? 'text-teal-700 font-semibold' : 'text-slate-500' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Customers
                </a>
                <a href="{{ route('owner.analytics.index') }}" class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('owner.analytics.*') ? 'text-teal-700 font-semibold' : 'text-slate-500' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6m6 0H3m6 0h6m0 0v-3a2 2 0 012-2h2a2 2 0 012 2v3m-6 0h6"/></svg>
                    Stats
                </a>
            @endif
        </div>
    </nav>

    @stack('scripts')
</body>
</html>
