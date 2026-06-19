<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;

test('category can be created', function () {
    $category = Category::factory()->create();

    expect($category)->toBeInstanceOf(Category::class);
    expect($category->name)->not->toBeEmpty();
});

test('category has subcategories', function () {
    $category = Category::factory()->create();
    Subcategory::factory()->count(2)->create(['category_id' => $category->id]);

    expect($category->subcategories)->toHaveCount(2);
});

test('category has products', function () {
    $category = Category::factory()->create();
    Product::factory()->count(3)->create(['category_id' => $category->id]);

    expect($category->products)->toHaveCount(3);
});

test('category has slug', function () {
    $category = Category::factory()->create([
        'name' => 'Excavator Attachments',
        'slug' => 'excavator-attachments',
    ]);

    expect($category->slug)->toBe('excavator-attachments');
});
