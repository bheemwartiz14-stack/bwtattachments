<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

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

            $category = Category::where('name', $categoryName)->first();

            if (! $category) {
                continue;
            }

            foreach ($items as $name) {

                Subcategory::updateOrCreate(
                    [
                        'name' => $name,
                        'category_id' => $category->id,
                    ],
                    []
                );
            }
        }
    }
}