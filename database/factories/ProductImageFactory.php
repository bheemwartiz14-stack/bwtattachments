<?php

namespace Database\Factories;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'image' => 'https://placehold.co/600x400',
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
