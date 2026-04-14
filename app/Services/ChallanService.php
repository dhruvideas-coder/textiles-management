<?php

namespace App\Services;

use App\Models\Challan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChallanService
{
    public function nextChallanNumber(int $shopId): string
    {
        $prefix = 'CHL-'.now()->format('Ym').'-';

        $last = Challan::withoutGlobalScope('shop')
            ->where('shop_id', $shopId)
            ->where('challan_number', 'like', "{$prefix}%")
            ->orderByDesc('challan_number')
            ->value('challan_number');

        $lastSequence = $last ? (int) substr($last, -4) : 0;
        $nextSequence = str_pad((string) ($lastSequence + 1), 4, '0', STR_PAD_LEFT);

        return "{$prefix}{$nextSequence}";
    }

    public function createChallan(User $user, array $payload): Challan
    {
        return DB::transaction(function () use ($payload, $user): Challan {
            $challan = Challan::create([
                'shop_id' => $user->shop_id,
                'user_id' => $user->id,
                'customer_id' => $payload['customer_id'] ?? null,
                'challan_number' => $payload['challan_number'] ?: $this->nextChallanNumber($user->shop_id),
                'challan_date' => $payload['challan_date'],
                'party_name' => $payload['party_name'],
                'broker_name' => $payload['broker_name'] ?? null,
                'remarks' => $payload['remarks'] ?? null,
                'status' => $payload['status'] ?? 'final',
            ]);

            foreach (($payload['items'] ?? []) as $index => $item) {
                $challan->items()->create([
                    'shop_id' => $user->shop_id,
                    'inventory_id' => $item['inventory_id'] ?? null,
                    'product_name' => $item['product_name'] ?? 'Textile Item',
                    'pieces' => (int) ($item['pieces'] ?? 0),
                    'meters' => (float) ($item['meters'] ?? 0),
                    'weight' => (float) ($item['weight'] ?? 0),
                    'remarks' => $item['remarks'] ?? null,
                    'sort_order' => $index,
                ]);
            }

            return $challan->load(['items.inventory', 'customer', 'shop', 'user']);
        });
    }
}
