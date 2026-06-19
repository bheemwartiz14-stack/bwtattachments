<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubcategorySeeder extends Seeder
{
    public function run(): void
    {
        $subcategories = [
            'Excavator Attachments' => ['Hydraulic Breakers', 'Grapples', 'Quick Couplers', 'Augers', 'Compactors'],
            'Wheel Loader Attachments' => ['Buckets', 'Forks', 'Log Grapples', 'Sweepers', 'Snow Plows'],
            'Wear Parts' => ['Cutting Edges', 'Teeth & Adapters', 'Shrouds', 'Deflectors', 'Side Cutters'],
            'Spare Parts' => ['Hydraulic Parts', 'Electrical Parts', 'Filters', 'Seals & Gaskets', 'Pins & Bushings'],
        ];

        foreach ($subcategories as $categoryName => $items) {
            $category = Category::whereSlug(Str::slug($categoryName))->first();

            if ($category) {
                foreach ($items as $name) {
                    Subcategory::firstOrCreate(
                        ['slug' => Str::slug($name)],
                        [
                            'category_id' => $category->id,
                            'name' => $name,
                            'status' => true,
                        ]
                    );
                }
            }
        }
    }
}
