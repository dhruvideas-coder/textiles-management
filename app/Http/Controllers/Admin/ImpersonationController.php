<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function start(User $user): RedirectResponse
    {
        abort_if($user->isSuperAdmin(), 422, 'Cannot impersonate another super admin.');

        session([
            'impersonator_id' => auth()->id(),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('status', 'Impersonation started.');
    }

    public function stop(): RedirectResponse
    {
        $impersonatorId = session('impersonator_id');
        abort_if(! $impersonatorId, 403);

        $admin = User::findOrFail($impersonatorId);
        Auth::login($admin);
        session()->forget('impersonator_id');

        return redirect()->route('admin.shops.index')->with('status', 'Impersonation stopped.');
    }
}
