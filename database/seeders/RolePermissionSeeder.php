<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // ROLES
        // =========================
        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web'
        ]);

        $wholesaleClient = Role::firstOrCreate([
            'name' => 'Wholesale Client',
            'guard_name' => 'web'
        ]);

        $retailer = Role::firstOrCreate([
            'name' => 'Retailer',
            'guard_name' => 'web'
        ]);

        $user = Role::firstOrCreate([
            'name' => 'User',
            'guard_name' => 'web'
        ]);

        // =========================
        // EXCLUDED PERMISSIONS FOR SUPER ADMIN
        // =========================
        $excludedForSuperAdmin = [
            'retailer.view',
            'retailer.create',
            'retailer.update',
            'retailer.delete',

            'user.view',
            'user.create',
            'user.update',
            'user.delete',
        ];

        // =========================
        // SUPER ADMIN
        // All permissions except retailer + user management
        // =========================
        $superAdmin->syncPermissions(
            Permission::whereNotIn('name', $excludedForSuperAdmin)->get()
        );

        // =========================
        // WHOLESALE CLIENT
        // Business owner level
        // =========================
        $wholesaleClient->syncPermissions([
            'product.view',
            'product.create',
            'product.update',
            'product.delete',

            'category.view',
            'category.create',
            'category.update',
            'category.delete',

            'subcategory.view',
            'subcategory.create',
            'subcategory.update',
            'subcategory.delete',

            'connection.view',
            'connection.create',
            'connection.update',
            'connection.delete',

            'client.view',
            'client.create',
            'client.update',
            'client.delete',
            'client.disable',

            'price.view',
            'price.update',

            'pdf.view',
            'pdf.upload',
            'pdf.download',
            'pdf.delete',

            'quotation.view',
            'quotation.create',
            'quotation.update',
            'quotation.delete',
            'quotation.download',

            'internal_note.view',
            'internal_note.update',

            'retailer.view',
            'retailer.create',
            'retailer.update',
            'retailer.delete',
        ]);

        // =========================
        // RETAILER
        // Limited access
        // =========================
        $retailer->syncPermissions([
            'product.view',
            'category.view',
            'subcategory.view',

            'price.view',

            'quotation.view',
            'quotation.create',

            'user.view',
            'user.create',
        ]);

        // =========================
        // PUBLIC USER
        // Basic access only
        // =========================
        $user->syncPermissions([
            'product.view',
            'category.view',
            'subcategory.view',
        ]);
    }
}