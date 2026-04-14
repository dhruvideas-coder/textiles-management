<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate(
            ['code' => Plan::CODE_FREE],
            [
                'name' => 'Free Plan',
                'monthly_price' => 0,
                'currency' => 'INR',
                'billing_cycle' => 'month',
                'max_bills_per_month' => 50,
                'max_staff_users' => 2,
                'features' => [
                    'bills' => true,
                    'challans' => true,
                    'inventory' => true,
                    'analytics' => true,
                    'pdf_export' => true,
                    'whatsapp_share' => true,
                    'staff_management' => true,
                ],
                'is_active' => true,
            ]
        );

        Plan::updateOrCreate(
            ['code' => Plan::CODE_PREMIUM],
            [
                'name' => 'Premium Plan',
                'monthly_price' => 1999,
                'currency' => 'INR',
                'billing_cycle' => 'month',
                'max_bills_per_month' => null,
                'max_staff_users' => null,
                'features' => [
                    'bills' => true,
                    'challans' => true,
                    'inventory' => true,
                    'analytics' => true,
                    'pdf_export' => true,
                    'whatsapp_share' => true,
                    'staff_management' => true,
                ],
                'is_active' => true,
            ]
        );
    }
}
