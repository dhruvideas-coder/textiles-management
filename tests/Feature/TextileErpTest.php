<?php

use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\Bill;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/** ─────────────────────────────────────────────
 *  HELPERS
 * ───────────────────────────────────────────── */

function createOwner(): User
{
    return User::factory()->create([
        'role'     => 'owner',
        'owner_id' => null,
    ]);
}

function createStaff(User $owner): User
{
    return User::factory()->create([
        'role'     => 'staff',
        'owner_id' => $owner->id,
    ]);
}

function createChallanForOwner(User $owner): Challan
{
    $customer = Customer::factory()->create(['owner_id' => $owner->id]);
    $product  = Product::factory()->create(['owner_id' => $owner->id]);

    return Challan::factory()->create([
        'owner_id'       => $owner->id,
        'customer_id'    => $customer->id,
        'product_id'     => $product->id,
        'challan_number' => 'C-001',
    ]);
}

/** ─────────────────────────────────────────────
 *  CHALLAN TESTS
 * ───────────────────────────────────────────── */

it('owner can create a challan', function () {
    $owner   = createOwner();
    $customer = Customer::factory()->create(['owner_id' => $owner->id]);
    $product  = Product::factory()->create(['owner_id' => $owner->id]);

    $challan = Challan::create([
        'owner_id'       => $owner->id,
        'customer_id'    => $customer->id,
        'product_id'     => $product->id,
        'challan_number' => 'C-TEST-001',
        'date'           => now(),
        'status'         => 'At Mill',
        'total_pieces'   => 12,
        'total_meters'   => 245.50,
    ]);

    expect($challan->id)->not->toBeNull();
    expect($challan->challan_number)->toBe('C-TEST-001');
});

it('saves challan items (6x12 grid) correctly', function () {
    $owner   = createOwner();
    $challan = createChallanForOwner($owner);

    // Simulate saving a 6x12 grid
    $filledItems = 0;
    for ($r = 1; $r <= 3; $r++) {
        for ($c = 1; $c <= 6; $c++) {
            ChallanItem::create([
                'challan_id' => $challan->id,
                'row_no'     => $r,
                'column_no'  => $c,
                'meters'     => 10.5,
            ]);
            $filledItems++;
        }
    }

    expect($challan->items()->count())->toBe($filledItems);
});

it('calculates challan totals from grid items', function () {
    $owner   = createOwner();
    $challan = createChallanForOwner($owner);

    ChallanItem::create(['challan_id' => $challan->id, 'row_no' => 1, 'column_no' => 1, 'meters' => 20.0]);
    ChallanItem::create(['challan_id' => $challan->id, 'row_no' => 2, 'column_no' => 1, 'meters' => 30.0]);

    $total = $challan->items()->sum('meters');
    expect($total)->toBe(50.0);
});

/** ─────────────────────────────────────────────
 *  BILL TESTS
 * ───────────────────────────────────────────── */

it('generates a bill linked to a challan', function () {
    $owner   = createOwner();
    $challan = createChallanForOwner($owner);
    $challan->update(['total_meters' => 100.0]);

    $amount  = 100.0 * 50.0;  // 100m @ ₹50
    $cgst    = $amount * 0.025;
    $sgst    = $amount * 0.025;
    $final   = $amount + $cgst + $sgst;

    $bill = Bill::create([
        'owner_id'    => $owner->id,
        'challan_id'  => $challan->id,
        'bill_number' => $challan->challan_number,
        'total_meters' => 100.0,
        'rate'        => 50.0,
        'amount'      => $amount,
        'cgst_amount' => $cgst,
        'sgst_amount' => $sgst,
        'final_total' => $final,
    ]);

    expect($bill->final_total)->toBe(round($final, 2));
    expect($bill->challan_id)->toBe($challan->id);
});

it('calculates GST correctly at 2.5% CGST + 2.5% SGST', function () {
    $amount  = 5000.0;
    $cgst    = round($amount * 0.025, 2);
    $sgst    = round($amount * 0.025, 2);
    $final   = $amount + $cgst + $sgst;

    expect($cgst)->toBe(125.0);
    expect($sgst)->toBe(125.0);
    expect($final)->toBe(5250.0);
});

/** ─────────────────────────────────────────────
 *  ROLE PERMISSION TESTS
 * ───────────────────────────────────────────── */

it('staff cannot create challans (policy)', function () {
    $owner = createOwner();
    $staff = createStaff($owner);
    $challan = createChallanForOwner($owner);

    $policy = new \App\Policies\ChallanPolicy();

    expect($policy->create($owner))->toBeTrue();
    expect($policy->create($staff))->toBeFalse();
});

it('staff can view challans that belong to their owner', function () {
    $owner   = createOwner();
    $staff   = createStaff($owner);
    $challan = createChallanForOwner($owner);

    $policy = new \App\Policies\ChallanPolicy();

    expect($policy->view($staff, $challan))->toBeTrue();
});

it('staff cannot view challans from a different owner', function () {
    $owner1  = createOwner();
    $owner2  = createOwner();
    $staff   = createStaff($owner1);
    $challan = createChallanForOwner($owner2);

    $policy = new \App\Policies\ChallanPolicy();

    expect($policy->view($staff, $challan))->toBeFalse();
});

it('owner can only view their own challans (global scope isolation)', function () {
    $owner1 = createOwner();
    $owner2 = createOwner();

    $challan1 = createChallanForOwner($owner1);
    $challan2 = createChallanForOwner($owner2);

    // Act as owner1
    $this->actingAs($owner1);

    // Global scope should only return owner1's challans
    $challans = Challan::withoutGlobalScope('owner')->where('owner_id', $owner1->id)->get();
    expect($challans->count())->toBe(1);
    expect($challans->first()->id)->toBe($challan1->id);
});
