<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Permission::create(['name' => 'product.view', 'guard_name' => 'web']);
    Permission::create(['name' => 'product.create', 'guard_name' => 'web']);
    Permission::create(['name' => 'user.view', 'guard_name' => 'web']);
    Permission::create(['name' => 'quotation.view', 'guard_name' => 'web']);
    Permission::create(['name' => 'quotation.create', 'guard_name' => 'web']);
    Permission::create(['name' => 'category.view', 'guard_name' => 'web']);
});

test('super admin has all permissions', function () {
    $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());

    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    expect($user->can('product.view'))->toBeTrue();
    expect($user->can('product.create'))->toBeTrue();
    expect($user->can('user.view'))->toBeTrue();
    expect($user->can('quotation.create'))->toBeTrue();
});

test('Wholesale has limited permissions', function () {
    $role = Role::create(['name' => 'Wholesale', 'guard_name' => 'web']);
    $role->givePermissionTo(['product.view', 'quotation.view', 'quotation.create', 'category.view']);

    $user = User::factory()->create();
    $user->assignRole('Wholesale');

    expect($user->can('product.view'))->toBeTrue();
    expect($user->can('quotation.create'))->toBeTrue();
    expect($user->can('product.create'))->toBeFalse();
    expect($user->can('user.view'))->toBeFalse();
});

test('admin can create products with permission', function () {
    $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());

    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    expect($user->can('product.create'))->toBeTrue();
});

test('client cannot create products', function () {
    $role = Role::create(['name' => 'Wholesale', 'guard_name' => 'web']);
    $role->givePermissionTo(['product.view', 'category.view']);

    $user = User::factory()->create();
    $user->assignRole('Wholesale');

    expect($user->can('product.create'))->toBeFalse();
});
