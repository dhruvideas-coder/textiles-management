@extends('layouts.app')
@section('title', 'Create Bill')

@section('content')
<form method="POST" action="{{ route('owner.bills.store') }}" class="mx-auto max-w-4xl space-y-6" x-data="billForm()">
    @csrf

    <div class="card">
        <h2 class="mb-5 text-lg font-bold text-slate-900">Bill Information</h2>
        <div class="grid gap-4 sm:grid-cols-4">
            <div>
                <label class="form-label">Bill Number</label>
                <input type="text" name="bill_number" value="{{ old('bill_number', $nextBillNumber) }}" class="form-input">
            </div>
             <div>
                <label class="form-label">Order No.</label>
                <input type="text" name="order_number" value="{{ old('order_number') }}" class="form-input" placeholder="e.g. 101">
            </div>
             <div>
                <label class="form-label">Challan No.</label>
                <input type="text" name="challan_number" value="{{ old('challan_number') }}" class="form-input" placeholder="e.g. 03">
            </div>
             <div>
                <label class="form-label">Broker Name</label>
                <input type="text" name="broker_name" value="{{ old('broker_name') }}" class="form-input" placeholder="e.g. Ravi">
            </div>
            <div>
                <label class="form-label">Bill Date <span class="text-red-500">*</span></label>
                <input type="date" name="bill_date" value="{{ old('bill_date', date('Y-m-d')) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Customer</label>
                <select name="customer_id" class="form-select">
                    <option value="">Walk-in Customer</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} {{ $c->phone ? "($c->phone)" : '' }}</option>
                    @endforeach
                </select>
            </div>
             <div>
                <label class="form-label">Delivered To</label>
                <input type="text" name="delivered_to" value="{{ old('delivered_to') }}" class="form-input" placeholder="e.g. Shop Name">
            </div>
            <div>
                <label class="form-label">Status <span class="text-red-500">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="final" {{ old('status') === 'final' ? 'selected' : '' }}>Final</option>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="paid" {{ old('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Items --}}
    <div class="card overflow-hidden p-0">
        <div class="border-b border-slate-100 px-5 py-4 flex items-center justify-between">
            <h2 class="font-bold text-slate-900">Items</h2>
            <button type="button" @click="addItem()" class="btn-secondary text-xs">+ Add Row</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-slate-100 bg-slate-50/50"><tr>
                    <th class="table-th w-8">#</th>
                    <th class="table-th">Description</th>
                    <th class="table-th hidden sm:table-cell">Product</th>
                    <th class="table-th w-20">Pcs</th>
                    <th class="table-th w-24">Meters</th>
                    <th class="table-th w-24">Rate</th>
                    <th class="table-th w-28 text-right">Amount</th>
                    <th class="table-th w-10"></th>
                </tr></thead>
                <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="border-b border-slate-50">
                            <td class="table-td text-xs text-slate-400" x-text="index + 1"></td>
                            <td class="table-td"><input type="text" :name="`items[${index}][description]`" x-model="item.description" class="form-input text-sm" required></td>
                            <td class="table-td hidden sm:table-cell">
                                <select :name="`items[${index}][product_id]`" x-model="item.product_id" class="form-select text-sm" @change="prefillFromProduct(index)">
                                    <option value="">—</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-rate="{{ $p->rate }}">{{ $p->name }} ({{ $p->current_stock_meters }}m)</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="table-td"><input type="number" :name="`items[${index}][pieces]`" x-model.number="item.pieces" class="form-input text-sm text-center" min="0"></td>
                            <td class="table-td"><input type="number" :name="`items[${index}][meters]`" x-model.number="item.meters" class="form-input text-sm text-center" min="0.01" step="0.01" required></td>
                            <td class="table-td"><input type="number" :name="`items[${index}][rate]`" x-model.number="item.rate" class="form-input text-sm text-center" min="0" step="0.01" required></td>
                            <td class="table-td text-right font-semibold text-sm" x-text="'₹' + (item.meters * item.rate).toFixed(2)"></td>
                            <td class="table-td"><button type="button" @click="removeItem(index)" class="text-red-400 hover:text-red-600" x-show="items.length > 1"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                        </tr>
                    </template>
                </tbody>
                <tfoot class="bg-slate-50">
                    <tr><td colspan="6" class="table-td text-right font-bold text-slate-900">Subtotal</td><td class="table-td text-right font-bold text-slate-900" x-text="'₹' + subtotal.toFixed(2)"></td><td></td></tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Charges --}}
    <div class="card">
        <h2 class="mb-5 text-lg font-bold text-slate-900">Additional Charges & Tax</h2>
        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="form-label">Discount (₹)</label>
                <input type="number" name="discount" x-model.number="discount" class="form-input" min="0" step="0.01">
            </div>
            <div>
                <label class="form-label">Transport (₹)</label>
                <input type="number" name="transport_charges" x-model.number="transport" class="form-input" min="0" step="0.01">
            </div>
            <div>
                <label class="form-label">Round-off (₹)</label>
                <input type="number" name="round_off" x-model.number="roundOff" class="form-input" step="0.01">
            </div>
            <div>
                <label class="form-label">CGST (₹)</label>
                <input type="number" name="cgst" x-model.number="cgst" class="form-input" min="0" step="0.01">
            </div>
            <div>
                <label class="form-label">SGST (₹)</label>
                <input type="number" name="sgst" x-model.number="sgst" class="form-input" min="0" step="0.01">
            </div>
            <div>
                <label class="form-label">IGST (₹)</label>
                <input type="number" name="igst" x-model.number="igst" class="form-input" min="0" step="0.01">
            </div>
        </div>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <div>
                <label class="form-label">Paid Amount (₹)</label>
                <input type="number" name="paid_amount" class="form-input" min="0" step="0.01">
            </div>
            <div>
                <label class="form-label">Notes</label>
                <textarea name="notes" rows="2" class="form-textarea">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Grand Total --}}
    <div class="card bg-gradient-to-r from-teal-700 to-emerald-700 text-white">
        <div class="flex items-center justify-between text-lg">
            <span class="font-bold">Grand Total</span>
            <span class="text-3xl font-extrabold" x-text="'₹' + grandTotal.toFixed(2)"></span>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Create Bill</button>
        <a href="{{ route('owner.bills.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
function billForm() {
    return {
        items: [{ description: '', product_id: '', pieces: 0, meters: 0, rate: 0 }],
        discount: 0, transport: 0, cgst: 0, sgst: 0, igst: 0, roundOff: 0,
        get subtotal() { return this.items.reduce((s, i) => s + (i.meters * i.rate), 0); },
        get grandTotal() { return this.subtotal - this.discount + this.transport + this.cgst + this.sgst + this.igst + this.roundOff; },
        addItem() { this.items.push({ description: '', product_id: '', pieces: 0, meters: 0, rate: 0 }); },
        removeItem(i) { if (this.items.length > 1) this.items.splice(i, 1); },
        prefillFromProduct(i) {
            const sel = document.querySelector(`select[name="items[${i}][product_id]"]`);
            const opt = sel?.selectedOptions[0];
            if (opt && opt.value) {
                this.items[i].description = opt.dataset.name || '';
                this.items[i].rate = parseFloat(opt.dataset.rate) || 0;
            }
        }
    }
}
</script>
@endpush
@endsection
