<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Wholesale Client', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Public User', 'guard_name' => 'web']);
    }
}
