<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Challan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    protected $model = Bill::class;

    public function definition(): array
    {
        $meters = $this->faker->randomFloat(2, 50, 300);
        $rate   = $this->faker->randomFloat(2, 40, 120);
        $amount = $meters * $rate;
        $cgst   = round($amount * 0.025, 2);
        $sgst   = round($amount * 0.025, 2);

        return [
            'owner_id'    => User::factory(),
            'challan_id'  => Challan::factory(),
            'bill_number' => 'B-' . $this->faker->unique()->numerify('####'),
            'total_meters' => $meters,
            'rate'        => $rate,
            'amount'      => round($amount, 2),
            'cgst_amount' => $cgst,
            'sgst_amount' => $sgst,
            'final_total' => round($amount + $cgst + $sgst, 2),
        ];
    }
}
