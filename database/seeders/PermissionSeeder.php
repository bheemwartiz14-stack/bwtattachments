<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            'wholesale_client.view',
            'wholesale_client.create',
            'wholesale_client.update',
            'wholesale_client.delete',

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

            'user.view',
            'user.create',
            'user.update',
            'user.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}