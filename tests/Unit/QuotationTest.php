<?php

use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\User;

test('quotation can be created', function () {
    $user = User::factory()->create();
    $quotation = Quotation::factory()->create(['user_id' => $user->id]);

    expect($quotation)->toBeInstanceOf(Quotation::class);
    expect($quotation->quotation_number)->not->toBeEmpty();
});

test('quotation belongs to user', function () {
    $user = User::factory()->create();
    $quotation = Quotation::factory()->create(['user_id' => $user->id]);

    expect($quotation->user)->toBeInstanceOf(User::class);
    expect($quotation->user->id)->toEqual($user->id);
});

test('quotation has items', function () {
    $user = User::factory()->create();
    $quotation = Quotation::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create();
    QuotationItem::factory()->count(3)->create([
        'quotation_id' => $quotation->id,
        'product_id' => $product->id,
    ]);

    expect($quotation->items)->toHaveCount(3);
    expect($quotation->items->first())->toBeInstanceOf(QuotationItem::class);
});

test('quotation item belongs to product', function () {
    $product = Product::factory()->create();
    $quotation = Quotation::factory()->create(['user_id' => User::factory()->create()->id]);
    $item = QuotationItem::factory()->create([
        'quotation_id' => $quotation->id,
        'product_id' => $product->id,
    ]);

    expect($item->product)->toBeInstanceOf(Product::class);
});
