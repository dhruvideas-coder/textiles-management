<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $defaultRate = $this->faker->randomFloat(2, 30, 150);
        return [
            'owner_id'       => User::factory(),
            'name'           => $this->faker->randomElement(['PXP Vichitra', 'Dola Silk', 'Chinon', 'Satin', 'Chiffon', 'Georgette']),
            'default_rate'   => $defaultRate,
            'last_used_rate' => $defaultRate,
        ];
    }
}
