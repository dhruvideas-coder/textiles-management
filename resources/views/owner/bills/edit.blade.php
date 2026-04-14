@extends('layouts.app')
@section('title', 'Edit Bill ' . $bill->bill_number)

@section('content')
<form method="POST" action="{{ route('owner.bills.update', $bill) }}" class="mx-auto max-w-4xl space-y-6" x-data="billEditForm()">
    @csrf @method('PUT')

    <div class="card border-amber-200 bg-amber-50">
        <p class="text-sm text-amber-800"><strong>Note:</strong> Direct bill editing is disabled for data integrity. Use the <strong>Duplicate</strong> feature to create a revised bill.</p>
    </div>

    <div class="card">
        <h2 class="mb-5 text-lg font-bold text-slate-900">Bill Information</h2>
        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="form-label">Bill Number</label>
                <input type="text" name="bill_number" value="{{ $bill->bill_number }}" class="form-input" readonly>
            </div>
            <div>
                <label class="form-label">Bill Date</label>
                <input type="date" name="bill_date" value="{{ $bill->bill_date?->format('Y-m-d') }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Customer</label>
                <select name="customer_id" class="form-select">
                    <option value="">Walk-in</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ $bill->customer_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="draft" {{ $bill->status === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="final" {{ $bill->status === 'final' ? 'selected' : '' }}>Final</option>
                    <option value="paid" {{ $bill->status === 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Items --}}
    <div class="card overflow-hidden p-0">
        <div class="border-b border-slate-100 px-5 py-4"><h2 class="font-bold text-slate-900">Items</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-slate-100 bg-slate-50/50"><tr>
                    <th class="table-th">#</th><th class="table-th">Description</th><th class="table-th w-20">Pcs</th><th class="table-th w-24">Meters</th><th class="table-th w-24">Rate</th><th class="table-th w-28 text-right">Amount</th>
                </tr></thead>
                <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="border-b border-slate-50">
                            <td class="table-td text-slate-400" x-text="index + 1"></td>
                            <td class="table-td"><input type="text" :name="`items[${index}][description]`" x-model="item.description" class="form-input text-sm" required></td>
                            <td class="table-td"><input type="number" :name="`items[${index}][pieces]`" x-model.number="item.pieces" class="form-input text-sm text-center" min="0"></td>
                            <td class="table-td"><input type="number" :name="`items[${index}][meters]`" x-model.number="item.meters" class="form-input text-sm text-center" min="0.01" step="0.01" required></td>
                            <td class="table-td"><input type="number" :name="`items[${index}][rate]`" x-model.number="item.rate" class="form-input text-sm text-center" min="0" step="0.01" required></td>
                            <td class="table-td text-right font-semibold text-sm" x-text="'₹' + (item.meters * item.rate).toFixed(2)"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Save Changes</button>
        <a href="{{ route('owner.bills.show', $bill) }}" class="btn-secondary">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
function billEditForm() {
    return {
        items: @json($bill->items->map(fn($i) => ['description' => $i->description, 'pieces' => $i->pieces, 'meters' => (float)$i->meters, 'rate' => (float)$i->rate])),
    }
}
</script>
@endpush
@endsection
