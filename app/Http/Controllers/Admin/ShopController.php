<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Shop;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        return view('admin.shops.index', [
            'shops' => Shop::with(['owner', 'subscription.plan'])->latest()->paginate(20),
            'plans' => Plan::orderBy('monthly_price')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.shops.create', [
            'plans' => Plan::where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:shops,slug'],
            'code' => ['nullable', 'string', 'max:255', 'unique:shops,code'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gstin' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:120'],
            'pincode' => ['nullable', 'string', 'max:12'],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'email', 'unique:users,email'],
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        DB::transaction(function () use ($validated): void {
            $owner = User::create([
                'name' => $validated['owner_name'],
                'email' => $validated['owner_email'],
                'password' => Hash::make(Str::password()),
                'is_active' => true,
            ]);
            $owner->assignRole(User::ROLE_OWNER);

            $shop = Shop::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'] ?: Str::slug($validated['name']).'-'.Str::lower(Str::random(4)),
                'code' => $validated['code'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'gstin' => $validated['gstin'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'pincode' => $validated['pincode'] ?? null,
                'owner_user_id' => $owner->id,
                'is_active' => true,
            ]);

            $owner->shop_id = $shop->id;
            $owner->save();

            Subscription::create([
                'shop_id' => $shop->id,
                'plan_id' => $validated['plan_id'],
                'status' => 'active',
                'started_at' => now()->toDateString(),
                'current_period_start' => now()->startOfMonth()->toDateString(),
                'current_period_end' => now()->endOfMonth()->toDateString(),
            ]);
        });

        return redirect()->route('admin.shops.index')->with('status', 'Shop created successfully.');
    }

    public function show(Shop $shop): View
    {
        return view('admin.shops.show', [
            'shop' => $shop->load(['owner', 'users.roles', 'subscription.plan']),
        ]);
    }

    public function edit(Shop $shop): View
    {
        return view('admin.shops.edit', [
            'shop' => $shop,
            'plans' => Plan::where('is_active', true)->get(),
            'currentSubscription' => $shop->subscription()->latest()->first(),
        ]);
    }

    public function update(Request $request, Shop $shop): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:shops,slug,'.$shop->id],
            'code' => ['nullable', 'string', 'max:255', 'unique:shops,code,'.$shop->id],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gstin' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:120'],
            'pincode' => ['nullable', 'string', 'max:12'],
            'theme_color' => ['nullable', 'string', 'max:20'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $shop->update([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($shop->owner) {
            $shop->owner->update(['is_active' => $shop->is_active]);
        }

        return redirect()->route('admin.shops.index')->with('status', 'Shop updated.');
    }

    public function destroy(Shop $shop): RedirectResponse
    {
        $shop->delete();

        return redirect()->route('admin.shops.index')->with('status', 'Shop deleted.');
    }
}
