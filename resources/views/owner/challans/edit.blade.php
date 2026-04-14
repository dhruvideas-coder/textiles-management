@extends('layouts.app')
@section('title', 'Edit Challan ' . $challan->challan_number)

@section('content')
<form method="POST" action="{{ route('owner.challans.update', $challan) }}" class="mx-auto max-w-4xl space-y-6" x-data="challanEditForm()">
    @csrf @method('PUT')

    <div class="card border-amber-200 bg-amber-50">
        <p class="text-sm text-amber-800">You are editing an existing challan.</p>
    </div>

    <div class="card">
        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="form-label">Challan Number</label>
                <input type="text" name="challan_number" value="{{ $challan->challan_number }}" class="form-input" readonly>
            </div>
            <div>
                <label class="form-label">Challan Date <span class="text-red-500">*</span></label>
                <input type="date" name="challan_date" value="{{ $challan->challan_date?->format('Y-m-d') }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Party Name <span class="text-red-500">*</span></label>
                <input type="text" name="party_name" value="{{ $challan->party_name }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Status <span class="text-red-500">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="final" {{ $challan->status === 'final' ? 'selected' : '' }}>Final</option>
                    <option value="draft" {{ $challan->status === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card overflow-hidden p-0">
        <div class="border-b border-slate-100 px-5 py-4"><h2 class="font-bold text-slate-900">Items</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-slate-100 bg-slate-50/50"><tr>
                    <th class="table-th">#</th><th class="table-th">Product</th><th class="table-th w-24">Pcs</th><th class="table-th w-24">Meters</th><th class="table-th w-24">Weight</th>
                </tr></thead>
                <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="border-b border-slate-50">
                            <td class="table-td text-slate-400" x-text="index + 1"></td>
                            <td class="table-td"><input type="text" :name="`items[${index}][product_name]`" x-model="item.product_name" class="form-input text-sm" required></td>
                            <td class="table-td"><input type="number" :name="`items[${index}][pieces]`" x-model.number="item.pieces" class="form-input text-sm text-center" min="0"></td>
                            <td class="table-td"><input type="number" :name="`items[${index}][meters]`" x-model.number="item.meters" class="form-input text-sm text-center" min="0" step="0.01"></td>
                            <td class="table-td"><input type="number" :name="`items[${index}][weight]`" x-model.number="item.weight" class="form-input text-sm text-center" min="0" step="0.01"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Save Changes</button>
        <a href="{{ route('owner.challans.show', $challan) }}" class="btn-secondary">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
function challanEditForm() {
    return {
        items: @json($challan->items->map(fn($i) => ['product_name' => $i->product_name, 'pieces' => $i->pieces, 'meters' => (float)$i->meters, 'weight' => (float)$i->weight])),
    }
}
</script>
@endpush
@endsection
