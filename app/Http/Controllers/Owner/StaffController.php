<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function __construct()
    {
    }

    public function index(): View
    {
        $shop = auth()->user()->shop;
        return view('owner.staff.index', [
            'staff' => User::where('shop_id', $shop->id)->role(User::ROLE_STAFF)->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('owner.staff.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $shop = auth()->user()->shop;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $staff = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'shop_id' => $shop->id,
            'password' => Hash::make(Str::password()),
            'is_active' => $request->boolean('is_active', true),
        ]);
        $staff->syncRoles([User::ROLE_STAFF]);

        return redirect()->route('owner.staff.index')->with('status', 'Staff user created.');
    }

    public function show(User $staff): View
    {
        abort_unless($staff->shop_id === auth()->user()->shop_id && $staff->hasRole(User::ROLE_STAFF), 404);

        return view('owner.staff.show', ['staff' => $staff]);
    }

    public function edit(User $staff): View
    {
        abort_unless($staff->shop_id === auth()->user()->shop_id && $staff->hasRole(User::ROLE_STAFF), 404);

        return view('owner.staff.edit', ['staff' => $staff]);
    }

    public function update(Request $request, User $staff): RedirectResponse
    {
        abort_unless($staff->shop_id === auth()->user()->shop_id && $staff->hasRole(User::ROLE_STAFF), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$staff->id],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $staff->update([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('owner.staff.index')->with('status', 'Staff updated.');
    }

    public function destroy(User $staff): RedirectResponse
    {
        abort_unless($staff->shop_id === auth()->user()->shop_id && $staff->hasRole(User::ROLE_STAFF), 404);

        $staff->delete();

        return redirect()->route('owner.staff.index')->with('status', 'Staff removed.');
    }
}
