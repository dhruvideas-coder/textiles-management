<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with(['shop', 'roles'])->latest();

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search): void {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return view('admin.users.index', [
            'users' => $query->paginate(20)->withQueryString(),
            'shops' => Shop::orderBy('name')->get(),
            'roles' => [User::ROLE_SUPER_ADMIN, User::ROLE_OWNER, User::ROLE_STAFF],
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'shops' => Shop::orderBy('name')->get(),
            'roles' => [User::ROLE_OWNER, User::ROLE_STAFF],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:'.implode(',', [User::ROLE_OWNER, User::ROLE_STAFF, User::ROLE_SUPER_ADMIN])],
            'shop_id' => ['nullable', 'exists:shops,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'shop_id' => $validated['role'] === User::ROLE_SUPER_ADMIN ? null : ($validated['shop_id'] ?? null),
            'password' => Hash::make(Str::password()),
            'is_active' => $request->boolean('is_active', true),
        ]);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')->with('status', 'User created.');
    }

    public function show(User $user): View
    {
        return view('admin.users.show', [
            'user' => $user->load(['shop', 'roles']),
        ]);
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user->load('roles'),
            'shops' => Shop::orderBy('name')->get(),
            'roles' => [User::ROLE_SUPER_ADMIN, User::ROLE_OWNER, User::ROLE_STAFF],
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:'.implode(',', [User::ROLE_SUPER_ADMIN, User::ROLE_OWNER, User::ROLE_STAFF])],
            'shop_id' => ['nullable', 'exists:shops,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'shop_id' => $validated['role'] === User::ROLE_SUPER_ADMIN ? null : ($validated['shop_id'] ?? null),
            'is_active' => $request->boolean('is_active'),
        ]);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')->with('status', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted.');
    }
}
