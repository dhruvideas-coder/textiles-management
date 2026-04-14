@extends('layouts.app')
@section('title', 'Create Challan')

@section('content')
<form method="POST" action="{{ route('owner.challans.store') }}" class="mx-auto max-w-4xl space-y-6" x-data="challanForm()">
    @csrf

    <div class="card">
        <h2 class="mb-5 text-lg font-bold text-slate-900">Challan Information</h2>
        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="form-label">Challan Number</label>
                <input type="text" name="challan_number" value="{{ old('challan_number', $nextChallanNumber) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Challan Date <span class="text-red-500">*</span></label>
                <input type="date" name="challan_date" value="{{ old('challan_date', date('Y-m-d')) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Party Name <span class="text-red-500">*</span></label>
                <input type="text" name="party_name" value="{{ old('party_name') }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Customer (Optional)</label>
                <select name="customer_id" class="form-select">
                    <option value="">— Select Customer —</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Broker Name</label>
                <input type="text" name="broker_name" value="{{ old('broker_name') }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Status <span class="text-red-500">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="final" {{ old('status') === 'final' ? 'selected' : '' }}>Final</option>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
        </div>
        <div class="mt-4">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" rows="2" class="form-textarea">{{ old('remarks') }}</textarea>
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
                    <th class="table-th">Product Name</th>
                    <th class="table-th hidden sm:table-cell">Inventory</th>
                    <th class="table-th w-24">Pcs</th>
                    <th class="table-th w-24">Meters</th>
                    <th class="table-th w-24">Weight</th>
                    <th class="table-th w-10"></th>
                </tr></thead>
                <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="border-b border-slate-50">
                            <td class="table-td text-xs text-slate-400" x-text="index + 1"></td>
                            <td class="table-td"><input type="text" :name="`items[${index}][product_name]`" x-model="item.product_name" class="form-input text-sm" required></td>
                            <td class="table-td hidden sm:table-cell">
                                <select :name="`items[${index}][inventory_id]`" x-model="item.inventory_id" class="form-select text-sm" @change="prefillFromInventory(index)">
                                    <option value="">—</option>
                                    @foreach($inventoryItems as $inv)
                                        <option value="{{ $inv->id }}" data-name="{{ $inv->name }}">{{ $inv->name }} ({{ $inv->current_stock_meters }}m)</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="table-td"><input type="number" :name="`items[${index}][pieces]`" x-model.number="item.pieces" class="form-input text-sm text-center" min="0"></td>
                            <td class="table-td"><input type="number" :name="`items[${index}][meters]`" x-model.number="item.meters" class="form-input text-sm text-center" min="0" step="0.01"></td>
                            <td class="table-td"><input type="number" :name="`items[${index}][weight]`" x-model.number="item.weight" class="form-input text-sm text-center" min="0" step="0.01"></td>
                            <td class="table-td"><button type="button" @click="removeItem(index)" class="text-red-400 hover:text-red-600" x-show="items.length > 1"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Create Challan</button>
        <a href="{{ route('owner.challans.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
function challanForm() {
    return {
        items: [{ product_name: '', inventory_id: '', pieces: 0, meters: 0, weight: 0 }],
        addItem() { this.items.push({ product_name: '', inventory_id: '', pieces: 0, meters: 0, weight: 0 }); },
        removeItem(i) { if (this.items.length > 1) this.items.splice(i, 1); },
        prefillFromInventory(i) {
            const sel = document.querySelector(`select[name="items[${i}][inventory_id]"]`);
            const opt = sel?.selectedOptions[0];
            if (opt && opt.value) {
                this.items[i].product_name = opt.dataset.name || '';
            }
        }
    }
}
</script>
@endpush
@endsection
