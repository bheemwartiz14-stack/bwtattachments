<?php

namespace Database\Factories;

use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubcategoryFactory extends Factory
{
    protected $model = Subcategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'category_id' => \App\Models\Category::inRandomOrder()->first()?->id
                ?? \App\Models\Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'status' => true,
        ];
    }
}
