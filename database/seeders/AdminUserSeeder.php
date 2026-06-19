<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
       $admin = User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'id' => (string) Str::uuid(),
                'name' => env('ADMIN_NAME', 'Admin'),
                'username' => env('ADMIN_USERNAME', 'admin'),
                'password' => bcrypt(env('ADMIN_PASSWORD', 'password')),
                'status' => true,
                'is_first_time' => false,
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole(Role::whereName('Super Admin')->first());
    }
}
