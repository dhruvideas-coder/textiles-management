<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BillService
{
    public function __construct(private readonly SubscriptionService $subscriptionService)
    {
    }

    public function nextBillNumber(int $shopId): string
    {
        $prefix = 'BIL-'.now()->format('Ym').'-';

        $last = Bill::withoutGlobalScope('shop')
            ->where('shop_id', $shopId)
            ->where('bill_number', 'like', "{$prefix}%")
            ->orderByDesc('bill_number')
            ->value('bill_number');

        $lastSequence = $last ? (int) substr($last, -4) : 0;
        $nextSequence = str_pad((string) ($lastSequence + 1), 4, '0', STR_PAD_LEFT);

        return "{$prefix}{$nextSequence}";
    }

    public function calculateTotals(array $items, array $charges): array
    {
        $normalizedItems = collect($items)->map(function (array $item): array {
            $meters = (float) ($item['meters'] ?? 0);
            $rate = (float) ($item['rate'] ?? 0);
            $amount = round($meters * $rate, 2);

            return [
                'inventory_id' => $item['inventory_id'] ?? null,
                'description' => $item['description'] ?? 'Textile Item',
                'pieces' => (int) ($item['pieces'] ?? 0),
                'meters' => $meters,
                'rate' => $rate,
                'amount' => $amount,
            ];
        })->values();

        $subtotal = round($normalizedItems->sum('amount'), 2);
        $discount = (float) ($charges['discount'] ?? 0);
        $transportCharges = (float) ($charges['transport_charges'] ?? 0);
        $cgst = (float) ($charges['cgst'] ?? 0);
        $sgst = (float) ($charges['sgst'] ?? 0);
        $igst = (float) ($charges['igst'] ?? 0);
        $taxTotal = round($cgst + $sgst + $igst, 2);
        $roundOff = (float) ($charges['round_off'] ?? 0);
        $total = round($subtotal - $discount + $transportCharges + $taxTotal + $roundOff, 2);

        return [
            'items' => $normalizedItems->all(),
            'totals' => [
                'subtotal' => $subtotal,
                'discount' => $discount,
                'transport_charges' => $transportCharges,
                'cgst' => $cgst,
                'sgst' => $sgst,
                'igst' => $igst,
                'tax_total' => $taxTotal,
                'round_off' => $roundOff,
                'total' => $total,
            ],
        ];
    }

    public function createBill(User $user, array $payload): Bill
    {
        $shop = $user->shop;
        $this->subscriptionService->assertCanCreateBill($shop);

        return DB::transaction(function () use ($payload, $shop, $user): Bill {
            $calculated = $this->calculateTotals($payload['items'], $payload);

            $bill = Bill::create([
                'shop_id' => $shop->id,
                'user_id' => $user->id,
                'customer_id' => $payload['customer_id'] ?? null,
                'bill_number' => $payload['bill_number'] ?: $this->nextBillNumber($shop->id),
                'bill_date' => $payload['bill_date'],
                'due_date' => $payload['due_date'] ?? null,
                'status' => $payload['status'] ?? 'final',
                ...$calculated['totals'],
                'paid_amount' => (float) ($payload['paid_amount'] ?? 0),
                'notes' => $payload['notes'] ?? null,
            ]);

            foreach ($calculated['items'] as $index => $item) {
                $bill->items()->create([
                    'shop_id' => $shop->id,
                    ...$item,
                    'sort_order' => $index,
                ]);

                if (! empty($item['inventory_id']) && $item['meters'] > 0) {
                    Inventory::where('id', $item['inventory_id'])
                        ->where('shop_id', $shop->id)
                        ->decrement('current_stock_meters', $item['meters']);
                }
            }

            $this->subscriptionService->incrementUsage($shop, 'bills');

            return $bill->load(['customer', 'items.inventory', 'shop', 'user']);
        });
    }

    public function duplicateBill(Bill $bill, User $user): Bill
    {
        return $this->createBill($user, [
            'bill_number' => null,
            'bill_date' => now()->toDateString(),
            'due_date' => null,
            'customer_id' => $bill->customer_id,
            'status' => 'draft',
            'discount' => $bill->discount,
            'transport_charges' => $bill->transport_charges,
            'cgst' => $bill->cgst,
            'sgst' => $bill->sgst,
            'igst' => $bill->igst,
            'round_off' => $bill->round_off,
            'paid_amount' => 0,
            'notes' => $bill->notes,
            'items' => $bill->items->map(fn ($item) => [
                'inventory_id' => $item->inventory_id,
                'description' => $item->description,
                'pieces' => $item->pieces,
                'meters' => $item->meters,
                'rate' => $item->rate,
            ])->all(),
        ]);
    }
}
