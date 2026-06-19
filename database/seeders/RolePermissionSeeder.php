<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::whereName('Super Admin')->first();
        $wholesaleClient = Role::whereName('Wholesale Client')->first();
        $publicUser = Role::whereName('Public User')->first();

        $superAdmin->syncPermissions(Permission::all());

        $wholesaleClient->syncPermissions([
            'product.view',
            'category.view',
            'subcategory.view',
            'connection.view',
            'price.view',
            'pdf.view',
            'pdf.download',
            'quotation.view',
            'quotation.create',
            'quotation.update',
        ]);

        $publicUser->syncPermissions([
            'product.view',
            'category.view',
            'subcategory.view',
            'connection.view',
        ]);
    }
}
