<?php

namespace Database\Factories;

use App\Models\Quotation;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuotationFactory extends Factory
{
    protected $model = Quotation::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'quotation_number' => 'QTN-' . date('Ymd') . '-' . fake()->unique()->randomNumber(5),
            'margin_percentage' => fake()->randomFloat(2, 5, 30),
            'pdf_file' => null,
            'status' => 'draft',
        ];
    }
}
