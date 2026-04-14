<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Inventory::query()->orderBy('name');

        if ($search = $request->string('search')->toString()) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        }

        return view('owner.inventory.index', [
            'inventory' => $query->paginate(20)->withQueryString(),
            'lowStock' => Inventory::all()->filter->isLowStock(),
        ]);
    }

    public function create(): View
    {
        return view('owner.inventory.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'current_stock_meters' => ['required', 'numeric', 'min:0'],
            'low_stock_threshold' => ['nullable', 'numeric', 'min:0'],
            'rate' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:30'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Inventory::create([
            ...$validated,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('owner.inventory.index')->with('status', 'Inventory item created.');
    }

    public function show(Inventory $inventory): View
    {
        return view('owner.inventory.show', ['item' => $inventory]);
    }

    public function edit(Inventory $inventory): View
    {
        return view('owner.inventory.edit', ['item' => $inventory]);
    }

    public function update(Request $request, Inventory $inventory): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'current_stock_meters' => ['required', 'numeric', 'min:0'],
            'low_stock_threshold' => ['nullable', 'numeric', 'min:0'],
            'rate' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:30'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $inventory->update([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('owner.inventory.index')->with('status', 'Inventory updated.');
    }

    public function destroy(Inventory $inventory): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('staff'), 403, 'Staff cannot delete inventory.');
        $inventory->delete();

        return redirect()->route('owner.inventory.index')->with('status', 'Inventory deleted.');
    }
}
