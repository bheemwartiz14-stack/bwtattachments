<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;

describe('Authorization', function () {
    beforeEach(function () {
        $this->seed([PermissionSeeder::class, RoleSeeder::class, RolePermissionSeeder::class]);
    });

    test('admin can access admin dashboard', function () {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');
        $this->actingAs($admin);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
    });

    test('client cannot access admin dashboard', function () {
        $client = User::factory()->create();
        $client->assignRole('Wholesale Client');
        $this->actingAs($client);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(403);
    });

    test('guest gets redirected to login', function () {
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    });
});
