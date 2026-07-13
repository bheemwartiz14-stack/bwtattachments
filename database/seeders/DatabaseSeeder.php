<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            CategorySeeder::class,
            ConnectionSeeder::class,
            SubcategorySeeder::class,
            AdminUserSeeder::class,
            SiteSettingSeeder::class,
        ]);
    }
}
