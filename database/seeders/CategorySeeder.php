<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Excavator Attachments', 'Wheel Loader Attachments', 'Wear Parts', 'Spare Parts'];

        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['name' => $name]
            );
        }
    }
}
