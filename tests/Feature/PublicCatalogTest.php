<?php

use App\Models\Category;
use App\Models\Product;

test('public can view products', function () {
    $products = Product::factory()->count(3)->create();

    $response = $this->get(route('public.products.index'));

    $response->assertStatus(200);
});

test('public can view product detail', function () {
    $product = Product::factory()->create();

    $response = $this->get(route('public.products.show', $product));

    $response->assertStatus(200);
});

test('public cannot see prices', function () {
    $product = Product::factory()->create(['ddp_price' => 1500.00]);

    $response = $this->get(route('public.products.show', $product));

    $response->assertDontSee('$1,500');
    $response->assertDontSee('$1500');
});

test('public can filter by category', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    $response = $this->get(route('public.categories.show', $category));

    $response->assertStatus(200);
});

test('public sees login to view pricing', function () {
    $product = Product::factory()->create();

    $response = $this->get(route('public.products.show', $product));

    $response->assertSee('Login');
});
