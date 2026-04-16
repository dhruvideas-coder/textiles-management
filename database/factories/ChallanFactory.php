<?php

namespace Database\Factories;

use App\Models\Challan;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChallanFactory extends Factory
{
    protected $model = Challan::class;

    public function definition(): array
    {
        return [
            'owner_id'       => User::factory(),
            'customer_id'    => Customer::factory(),
            'product_id'     => Product::factory(),
            'challan_number' => 'C-' . $this->faker->unique()->numerify('####'),
            'broker'         => $this->faker->optional()->name(),
            'date'           => $this->faker->date(),
            'status'         => $this->faker->randomElement(['At Mill', 'Process House', 'In Stock', 'Billed']),
            'total_pieces'   => $this->faker->numberBetween(1, 72),
            'total_meters'   => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}
