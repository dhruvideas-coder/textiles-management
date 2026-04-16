@extends('layouts.app')
@section('title', 'Create Challan')

@section('content')
<form method="POST" action="{{ route('owner.challans.store') }}" class="mx-auto max-w-5xl space-y-6" x-data="challanForm()">
    @csrf

    <div class="card">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-bold text-slate-900">Challan Information</h2>
            <div class="bg-slate-100 px-3 py-1 rounded text-sm font-bold text-slate-600">DELIVERY CHALLAN</div>
        </div>
        <div class="grid gap-4 sm:grid-cols-4">
            <div>
                <label class="form-label">Challan Number</label>
                <input type="text" name="challan_number" value="{{ old('challan_number', $nextChallanNumber) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Order No.</label>
                <input type="text" name="order_number" value="{{ old('order_number') }}" class="form-input" placeholder="e.g. 81">
            </div>
            <div>
                <label class="form-label">Challan Date <span class="text-red-500">*</span></label>
                <input type="date" name="challan_date" value="{{ old('challan_date', date('Y-m-d')) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Broker Name</label>
                <input type="text" name="broker_name" value="{{ old('broker_name') }}" class="form-input" placeholder="e.g. Ravi">
            </div>
            <div class="sm:col-span-2">
                <label class="form-label">Party Name (M/s.) <span class="text-red-500">*</span></label>
                <input type="text" name="party_name" value="{{ old('party_name') }}" class="form-input" required placeholder="e.g. DURGA SAREES">
            </div>
            <div class="sm:col-span-2">
                <label class="form-label">Customer (Optional Link)</label>
                <select name="customer_id" class="form-select">
                    <option value="">— Select Customer —</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-3 text-right flex items-end">
                 <div class="flex-1 mr-4">
                    <label class="form-label text-left">Remarks</label>
                    <input type="text" name="remarks" value="{{ old('remarks') }}" class="form-input" placeholder="e.g. No Dyeing Guarantee">
                </div>
                 <div>
                    <label class="form-label text-left">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="final" {{ old('status') === 'final' ? 'selected' : '' }}>Final</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Items --}}
    <div class="space-y-6">
        <template x-for="(item, index) in items" :key="index">
            <div class="card p-0 overflow-hidden border-2 border-slate-200">
                <div class="bg-slate-50 border-b border-slate-200 px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-4 flex-1">
                        <span class="h-8 w-8 flex items-center justify-center rounded-full bg-slate-900 text-white text-sm font-bold" x-text="index + 1"></span>
                        <div class="flex-1 max-w-xs">
                             <label class="text-[10px] uppercase font-bold text-slate-500">Quality / Description</label>
                             <input type="text" :name="`items[${index}][product_name]`" x-model="item.product_name" class="form-input-sm w-full font-bold" required placeholder="Quality Name">
                        </div>
                        <div class="flex-1 max-w-xs">
                             <label class="text-[10px] uppercase font-bold text-slate-500">Linked Product</label>
                             <select :name="`items[${index}][product_id]`" x-model="item.product_id" class="form-select text-xs" @change="prefillFromProduct(index)">
                                <option value="">— Link Product —</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-name="{{ $p->name }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="button" @click="removeItem(index)" class="text-red-500 hover:bg-red-50 p-2 rounded" x-show="items.length > 1">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
                
                <div class="p-4 bg-white grid grid-cols-6 gap-2">
                    <template x-for="(m, mIndex) in item.measurements" :key="mIndex">
                        <div class="relative">
                            <span class="absolute left-2 top-2 text-[10px] text-slate-400 pointer-events-none" x-text="mIndex + 1"></span>
                            <input type="number" 
                                   :name="`items[${index}][measurements][]`" 
                                   x-model.number="item.measurements[mIndex]" 
                                   @input="calculateItemTotal(index)"
                                   class="form-input text-right pl-6 text-sm" 
                                   step="0.01" 
                                   placeholder="0.00">
                        </div>
                    </template>
                </div>

                <div class="bg-slate-50 border-t border-slate-200 px-4 py-3 flex flex-wrap items-center justify-end gap-6">
                    <div class="text-xs">
                        <span class="text-slate-500 uppercase font-bold">Total Pieces:</span>
                        <input type="hidden" :name="`items[${index}][pieces]`" x-model="item.pieces">
                        <span class="text-lg font-extrabold text-slate-900 ml-2" x-text="item.pieces"></span>
                    </div>
                    <div class="text-xs">
                        <span class="text-slate-500 uppercase font-bold">Total Meters:</span>
                        <input type="hidden" :name="`items[${index}][meters]`" x-model="item.meters">
                        <span class="text-lg font-extrabold text-teal-700 ml-2" x-text="item.meters.toFixed(2)"></span>
                    </div>
                    <div class="text-xs flex items-center gap-2">
                        <span class="text-slate-500 uppercase font-bold">Weight (KG):</span>
                        <input type="number" :name="`items[${index}][weight]`" x-model.number="item.weight" class="form-input-sm w-20 text-right" step="0.01">
                    </div>
                </div>
            </div>
        </template>
        
        <button type="button" @click="addItem()" class="w-full py-4 border-2 border-dashed border-slate-300 rounded-xl text-slate-500 font-bold hover:border-teal-500 hover:text-teal-600 transition flex items-center justify-center gap-2">
             <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
             Add Another Quality
        </button>
    </div>

    <div class="flex items-center gap-3 pt-6">
        <button type="submit" class="btn-primary px-12 py-3 text-lg">Create Challan</button>
        <a href="{{ route('owner.challans.index') }}" class="btn-secondary py-3">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
function challanForm() {
    return {
        items: [this.newItem()],
        newItem() {
            return {
                product_name: '',
                product_id: '',
                pieces: 0,
                meters: 0,
                weight: 0,
                measurements: Array(12).fill('') // Default 12 slots as per image
            };
        },
        addItem() {
            this.items.push(this.newItem());
        },
        removeItem(i) {
            if (this.items.length > 1) this.items.splice(i, 1);
        },
        prefillFromProduct(i) {
            const sel = document.querySelector(`select[name="items[${i}][product_id]"]`);
            const opt = sel?.selectedOptions[0];
            if (opt && opt.value) {
                this.items[i].product_name = opt.dataset.name || '';
            }
        },
        calculateItemTotal(i) {
            let total = 0;
            let count = 0;
            this.items[i].measurements.forEach(m => {
                const val = parseFloat(m);
                if (!isNaN(val) && val > 0) {
                    total += val;
                    count++;
                }
            });
            this.items[i].meters = total;
            this.items[i].pieces = count;
        }
    }
}
</script>
<style>
.form-input-sm {
    @apply block rounded-lg border-slate-200 px-3 py-1.5 text-sm ring-0 transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10;
}
</style>
@endpush
@endsection
