<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'product.view', 'product.create', 'product.update', 'product.delete',
            'category.view', 'category.create', 'category.update', 'category.delete',
            'subcategory.view', 'subcategory.create', 'subcategory.update', 'subcategory.delete',
            'connection.view', 'connection.create', 'connection.update', 'connection.delete',
            'client.view', 'client.create', 'client.update', 'client.delete', 'client.disable',
            'price.view', 'price.update',
            'pdf.view', 'pdf.upload', 'pdf.download', 'pdf.delete',
            'quotation.view', 'quotation.create', 'quotation.update', 'quotation.delete', 'quotation.download',
            'internal_note.view', 'internal_note.update',
            'retailer.view', 'retailer.create', 'retailer.update', 'retailer.delete',
            'user.view', 'user.create', 'user.update', 'user.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $wholesaleClient = Role::firstOrCreate(['name' => 'Wholesale', 'guard_name' => 'web']);
        $retailer = Role::firstOrCreate(['name' => 'Reseller', 'guard_name' => 'web']);
        $user = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        $excludedForSuperAdmin = [
            'retailer.view', 'retailer.create', 'retailer.update', 'retailer.delete',
            'user.view', 'user.create', 'user.update', 'user.delete',
        ];
        $superAdmin->syncPermissions(
            Permission::whereNotIn('name', $excludedForSuperAdmin)->get()
        );
        $wholesaleClient->syncPermissions([
            'product.view', 'product.create', 'product.update', 'product.delete',
            'category.view', 'category.create', 'category.update', 'category.delete',
            'subcategory.view', 'subcategory.create', 'subcategory.update', 'subcategory.delete',
            'connection.view', 'connection.create', 'connection.update', 'connection.delete',
            'client.view', 'client.create', 'client.update', 'client.delete', 'client.disable',
            'price.view', 'price.update',
            'pdf.view', 'pdf.upload', 'pdf.download', 'pdf.delete',
            'quotation.view', 'quotation.create', 'quotation.update', 'quotation.delete', 'quotation.download',
            'internal_note.view', 'internal_note.update',
            'retailer.view', 'retailer.create', 'retailer.update', 'retailer.delete',
        ]);
        $retailer->syncPermissions([
            'product.view', 'category.view', 'subcategory.view',
            'price.view',
            'quotation.view', 'quotation.create',
            'user.view', 'user.create',
        ]);
        $user->syncPermissions([
            'product.view', 'category.view', 'subcategory.view',
        ]);
    }
}
