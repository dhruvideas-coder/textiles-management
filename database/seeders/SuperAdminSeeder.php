<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('SUPER_ADMIN_EMAIL', 'admin@textilesaas.test');
        $name = env('SUPER_ADMIN_NAME', 'Super Admin');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'shop_id' => null,
                'is_active' => true,
                'password' => Hash::make(Str::password()),
            ]
        );

        $user->syncRoles([User::ROLE_SUPER_ADMIN]);
    }
}
