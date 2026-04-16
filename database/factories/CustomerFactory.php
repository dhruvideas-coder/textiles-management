<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'owner_id'      => User::factory(),
            'name'          => $this->faker->company(),
            'address'       => $this->faker->address(),
            'GSTIN'         => '24' . strtoupper($this->faker->bothify('??????????#?#?#')),
            'mobile_number' => $this->faker->phoneNumber(),
        ];
    }
}
