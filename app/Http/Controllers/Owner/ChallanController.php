<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Challan;
use App\Models\Customer;
use App\Models\Inventory;
use App\Services\ChallanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChallanController extends Controller
{
    public function __construct(private readonly ChallanService $challanService)
    {
    }

    public function index(Request $request): View
    {
        $query = Challan::with(['customer', 'user'])->latest('challan_date');

        if ($search = $request->string('search')->toString()) {
            $query->where('challan_number', 'like', "%{$search}%")
                ->orWhere('party_name', 'like', "%{$search}%");
        }

        return view('owner.challans.index', [
            'challans' => $query->paginate(20)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('owner.challans.create', [
            'customers' => Customer::orderBy('name')->get(),
            'inventoryItems' => Inventory::where('is_active', true)->orderBy('name')->get(),
            'nextChallanNumber' => $this->challanService->nextChallanNumber(auth()->user()->shop_id),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'challan_number' => ['nullable', 'string', 'max:255'],
            'challan_date' => ['required', 'date'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'party_name' => ['required', 'string', 'max:255'],
            'broker_name' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,final,cancelled'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.inventory_id' => ['nullable', 'exists:inventory,id'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.pieces' => ['nullable', 'numeric', 'min:0'],
            'items.*.meters' => ['nullable', 'numeric', 'min:0'],
            'items.*.weight' => ['nullable', 'numeric', 'min:0'],
            'items.*.remarks' => ['nullable', 'string'],
        ]);

        $challan = $this->challanService->createChallan(auth()->user(), $validated);

        return redirect()->route('owner.challans.show', $challan)->with('status', 'Challan created.');
    }

    public function show(Challan $challan): View
    {
        return view('owner.challans.show', [
            'challan' => $challan->load(['customer', 'items.inventory', 'shop', 'user']),
        ]);
    }

    public function edit(Challan $challan): View
    {
        return view('owner.challans.edit', [
            'challan' => $challan->load('items'),
            'customers' => Customer::orderBy('name')->get(),
            'inventoryItems' => Inventory::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Challan $challan): RedirectResponse
    {
        abort(422, 'Direct challan editing is disabled. Duplicate and create a new challan instead.');
    }

    public function destroy(Challan $challan): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('staff'), 403, 'Staff cannot delete challans.');
        $challan->delete();

        return redirect()->route('owner.challans.index')->with('status', 'Challan deleted.');
    }

    public function downloadPdf(Challan $challan)
    {
        $pdf = Pdf::loadView('owner.challans.pdf', [
            'challan' => $challan->load(['customer', 'items.inventory', 'shop', 'user']),
        ]);

        return $pdf->download("{$challan->challan_number}.pdf");
    }
}
