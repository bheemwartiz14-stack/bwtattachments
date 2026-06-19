<?php

use App\Models\Category;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Permission::create(['name' => 'product.create', 'guard_name' => 'web']);
    Permission::create(['name' => 'product.view', 'guard_name' => 'web']);
    Permission::create(['name' => 'product.update', 'guard_name' => 'web']);
    Permission::create(['name' => 'product.delete', 'guard_name' => 'web']);
    Permission::create(['name' => 'product.restore', 'guard_name' => 'web']);
});

test('admin can list products', function () {
    $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());

    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $response = $this->actingAs($user)->get(route('admin.products.index'));

    $response->assertStatus(200);
});

test('admin can view create product page', function () {
    $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());

    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $response = $this->actingAs($user)->get(route('admin.products.create'));

    $response->assertStatus(200);
});

test('admin can store product', function () {
    $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());

    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $category = Category::factory()->create();

    $response = $this->actingAs($user)->post(route('admin.products.store'), [
        'product_code' => 'TEST-001',
        'product_description' => 'Test Product',
        'category_id' => $category->id,
        'ddp_price' => 1000.00,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('products', [
        'product_code' => 'TEST-001',
    ]);
});

test('admin can soft delete product', function () {
    $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());

    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $product = \App\Models\Product::factory()->create();

    $response = $this->actingAs($user)->delete(route('admin.products.destroy', $product));

    $response->assertRedirect();
    expect(\App\Models\Product::find($product->id))->toBeNull();
    expect(\App\Models\Product::withTrashed()->find($product->id))->not->toBeNull();
});

test('admin can restore product', function () {
    $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());

    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $product = \App\Models\Product::factory()->create();
    $product->delete();

    $response = $this->actingAs($user)->post(route('admin.products.restore', $product));

    $response->assertRedirect();
    expect(\App\Models\Product::find($product->id))->not->toBeNull();
});
