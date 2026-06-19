<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'product_code' => fake()->unique()->bothify('PROD-#####'),
            'product_description' => fake()->sentence(),
            'category_id' => \App\Models\Category::inRandomOrder()->first()?->id
                ?? \App\Models\Category::factory(),
            'subcategory_id' => \App\Models\Subcategory::inRandomOrder()->first()?->id
                ?? \App\Models\Subcategory::factory(),
            'connection_id' => \App\Models\Connection::inRandomOrder()->first()?->id
                ?? \App\Models\Connection::factory(),
            'weight' => fake()->randomFloat(2, 10, 5000),
            'machine_weight' => fake()->randomFloat(2, 100, 50000),
            'hinges' => fake()->word(),
            'width' => fake()->randomFloat(2, 10, 200),
            'volume' => fake()->randomFloat(2, 0.1, 50),
            'cutting_edge_thickness' => fake()->randomFloat(2, 1, 50),
            'teeth' => fake()->word(),
            'stick_width' => fake()->randomFloat(2, 5, 100),
            'pin_center' => fake()->randomFloat(2, 5, 100),
            'pin_hole' => fake()->randomFloat(2, 1, 30),
            'ddp_price' => fake()->randomFloat(2, 100, 10000),
            'pdf_file' => null,
            'internal_notes' => null,
            'status' => true,
        ];
    }
}
