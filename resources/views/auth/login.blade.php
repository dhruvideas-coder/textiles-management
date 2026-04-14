<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — Textile SaaS</title>
    <meta name="description" content="Sign in to the Textile Billing & Shop Management Platform">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-teal-950 to-slate-900 flex items-center justify-center p-4" style="font-family:'Inter',sans-serif">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-teal-400 to-emerald-500 shadow-lg shadow-teal-500/30">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Textile SaaS</h1>
            <p class="mt-2 text-sm text-teal-300/80">Billing, Challan & Shop Management Platform</p>
        </div>

        {{-- Card --}}
        <div class="rounded-2xl bg-white/10 p-8 shadow-2xl ring-1 ring-white/20 backdrop-blur-xl">
            <h2 class="text-xl font-bold text-white">Welcome back</h2>
            <p class="mt-1 text-sm text-slate-300">Sign in with your Google account to continue.</p>

            @if ($errors->any())
                <div class="mt-4 rounded-xl border border-red-400/30 bg-red-500/20 px-4 py-3 text-sm text-red-200">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <a href="{{ route('auth.google.redirect') }}" id="google-sign-in-btn"
               class="mt-6 flex w-full items-center justify-center gap-3 rounded-xl bg-white px-4 py-3.5 text-sm font-semibold text-slate-900 shadow-lg transition hover:bg-slate-50 hover:shadow-xl active:scale-[0.98]">
                <svg class="h-5 w-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                Continue with Google
            </a>

            <div class="mt-6 flex items-center gap-3 text-xs text-slate-400">
                <div class="h-px flex-1 bg-white/10"></div>
                <span>Invite-only access</span>
                <div class="h-px flex-1 bg-white/10"></div>
            </div>

            <p class="mt-4 text-center text-xs text-slate-400/80">
                Access is restricted. Only users invited by the Super Admin can sign in. Unknown emails are blocked for security.
            </p>
        </div>

        <p class="mt-6 text-center text-xs text-slate-500">&copy; {{ date('Y') }} Textile SaaS. All rights reserved.</p>
    </div>

</body>
</html>
