<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Permission::create(['name' => 'category.create', 'guard_name' => 'web']);
    Permission::create(['name' => 'category.view', 'guard_name' => 'web']);
    Permission::create(['name' => 'category.update', 'guard_name' => 'web']);
    Permission::create(['name' => 'category.delete', 'guard_name' => 'web']);
});

test('admin can list categories', function () {
    $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());

    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $response = $this->actingAs($user)->get(route('admin.categories.index'));

    $response->assertStatus(200);
});

test('admin can create category', function () {
    $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());

    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $response = $this->actingAs($user)->post(route('admin.categories.store'), [
        'name' => 'Test Category',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
});

test('admin can update category', function () {
    $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());

    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $category = \App\Models\Category::factory()->create();

    $response = $this->actingAs($user)->put(route('admin.categories.update', $category), [
        'name' => 'Updated Category',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('categories', ['name' => 'Updated Category']);
});

test('admin can delete category', function () {
    $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());

    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $category = \App\Models\Category::factory()->create();

    $response = $this->actingAs($user)->delete(route('admin.categories.destroy', $category));

    $response->assertRedirect();
    expect(\App\Models\Category::find($category->id))->toBeNull();
});
