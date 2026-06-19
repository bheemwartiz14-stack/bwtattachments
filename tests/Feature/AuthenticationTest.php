<?php

use App\Models\User;

test('login page is accessible', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('login with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect();
    $this->assertAuthenticated();
});

test('login with invalid credentials', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors();
    $this->assertGuest();
});

test('logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect();
    $this->assertGuest();
});

test('authenticated user cannot access login page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/login');

    $response->assertRedirect();
});

test('guest cannot access admin dashboard', function () {
    $response = $this->get('/admin/dashboard');

    $response->assertRedirect('/login');
});

test('guest cannot access client dashboard', function () {
    $response = $this->get('/client/dashboard');

    $response->assertRedirect('/login');
});
