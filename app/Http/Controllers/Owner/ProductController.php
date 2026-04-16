<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()->orderBy('name');

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('design_number', 'like', "%{$search}%");
            });
        }

        return view('owner.products.index', [
            'products' => $query->paginate(20)->withQueryString(),
            'lowStock' => Product::all()->filter->isLowStock(),
        ]);
    }

    public function create(): View
    {
        return view('owner.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => ['nullable', 'string', 'max:255'],
            'design_number' => ['nullable', 'string', 'max:255'],
            'hsn_code' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'current_stock_meters' => ['required', 'numeric', 'min:0'],
            'low_stock_threshold' => ['nullable', 'numeric', 'min:0'],
            'rate' => ['required', 'numeric', 'min:0'],
            'purchase_rate' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:30'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Product::create([
            ...$validated,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('owner.products.index')->with('status', 'Product created.');
    }

    public function show(Product $product): View
    {
        return view('owner.products.show', ['product' => $product]);
    }

    public function edit(Product $product): View
    {
        return view('owner.products.edit', ['product' => $product]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => ['nullable', 'string', 'max:255'],
            'design_number' => ['nullable', 'string', 'max:255'],
            'hsn_code' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'current_stock_meters' => ['required', 'numeric', 'min:0'],
            'low_stock_threshold' => ['nullable', 'numeric', 'min:0'],
            'rate' => ['required', 'numeric', 'min:0'],
            'purchase_rate' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:30'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $product->update([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('owner.products.index')->with('status', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('staff'), 403, 'Staff cannot delete products.');
        $product->delete();

        return redirect()->route('owner.products.index')->with('status', 'Product deleted.');
    }
}
