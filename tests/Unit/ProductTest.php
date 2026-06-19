<?php

use App\Models\Category;
use App\Models\Connection;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Subcategory;

test('product can be created', function () {
    $product = Product::factory()->create();

    expect($product)->toBeInstanceOf(Product::class);
    expect($product->product_code)->not->toBeEmpty();
});

test('product belongs to category', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    expect($product->category)->toBeInstanceOf(Category::class);
});

test('product belongs to subcategory', function () {
    $category = Category::factory()->create();
    $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
    $product = Product::factory()->create(['subcategory_id' => $subcategory->id]);

    expect($product->subcategory)->toBeInstanceOf(Subcategory::class);
});

test('product belongs to connection', function () {
    $connection = Connection::factory()->create();
    $product = Product::factory()->create(['connection_id' => $connection->id]);

    expect($product->connection)->toBeInstanceOf(Connection::class);
});

test('product has images', function () {
    $product = Product::factory()->create();
    ProductImage::factory()->count(3)->create(['product_id' => $product->id]);

    expect($product->images)->toHaveCount(3);
    expect($product->images->first())->toBeInstanceOf(ProductImage::class);
});

test('product has soft delete', function () {
    $product = Product::factory()->create();
    $product->delete();

    expect(Product::find($product->id))->toBeNull();
    expect(Product::withTrashed()->find($product->id))->not->toBeNull();
});
