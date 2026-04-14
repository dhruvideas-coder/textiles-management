<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Inventory;
use App\Services\BillService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillController extends Controller
{
    public function __construct(private readonly BillService $billService)
    {
    }

    public function index(Request $request): View
    {
        $query = Bill::with(['customer', 'user'])->latest('bill_date');

        if ($search = $request->string('search')->toString()) {
            $query->where('bill_number', 'like', "%{$search}%");
        }

        if ($customer = $request->integer('customer_id')) {
            $query->where('customer_id', $customer);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('bill_date', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('bill_date', '<=', $request->date('date_to'));
        }

        return view('owner.bills.index', [
            'bills' => $query->paginate(20)->withQueryString(),
            'customers' => Customer::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('owner.bills.create', [
            'customers' => Customer::orderBy('name')->get(),
            'inventoryItems' => Inventory::where('is_active', true)->orderBy('name')->get(),
            'nextBillNumber' => $this->billService->nextBillNumber(auth()->user()->shop_id),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bill_number' => ['nullable', 'string', 'max:255'],
            'bill_date' => ['required', 'date'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'status' => ['required', 'in:draft,final,paid,cancelled'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'transport_charges' => ['nullable', 'numeric', 'min:0'],
            'cgst' => ['nullable', 'numeric', 'min:0'],
            'sgst' => ['nullable', 'numeric', 'min:0'],
            'igst' => ['nullable', 'numeric', 'min:0'],
            'round_off' => ['nullable', 'numeric'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.inventory_id' => ['nullable', 'exists:inventory,id'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.pieces' => ['nullable', 'numeric', 'min:0'],
            'items.*.meters' => ['required', 'numeric', 'min:0.01'],
            'items.*.rate' => ['required', 'numeric', 'min:0'],
        ]);

        $bill = $this->billService->createBill(auth()->user(), $validated);

        return redirect()->route('owner.bills.show', $bill)->with('status', 'Bill created.');
    }

    public function show(Bill $bill): View
    {
        return view('owner.bills.show', [
            'bill' => $bill->load(['customer', 'items.inventory', 'shop', 'user']),
        ]);
    }

    public function edit(Bill $bill): View
    {
        return view('owner.bills.edit', [
            'bill' => $bill->load('items'),
            'customers' => Customer::orderBy('name')->get(),
            'inventoryItems' => Inventory::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Bill $bill): RedirectResponse
    {
        abort(422, 'Direct bill editing is disabled. Use duplicate bill mode to revise safely.');
    }

    public function destroy(Bill $bill): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('staff'), 403, 'Staff cannot delete bills.');

        $bill->delete();

        return redirect()->route('owner.bills.index')->with('status', 'Bill deleted.');
    }

    public function duplicate(Bill $bill): RedirectResponse
    {
        $newBill = $this->billService->duplicateBill($bill->load('items'), auth()->user());

        return redirect()->route('owner.bills.edit', $newBill)->with('status', 'Bill duplicated in draft mode.');
    }

    public function downloadPdf(Bill $bill)
    {
        $pdf = Pdf::loadView('owner.bills.pdf', [
            'bill' => $bill->load(['customer', 'items.inventory', 'shop', 'user']),
        ]);

        return $pdf->download("{$bill->bill_number}.pdf");
    }

    public function printThermal(Bill $bill): View
    {
        return view('owner.bills.thermal', [
            'bill' => $bill->load(['customer', 'items.inventory', 'shop']),
        ]);
    }
}
