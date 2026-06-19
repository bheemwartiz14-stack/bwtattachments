<?php

namespace Database\Factories;

use App\Models\QuotationItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuotationItemFactory extends Factory
{
    protected $model = QuotationItem::class;

    public function definition(): array
    {
        return [
            'quotation_id' => \App\Models\Quotation::factory(),
            'product_id' => \App\Models\Product::factory(),
            'price' => fake()->randomFloat(2, 50, 5000),
            'quantity' => fake()->numberBetween(1, 10),
        ];
    }
}
