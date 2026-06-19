<?php

namespace Database\Factories;

use App\Models\Connection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ConnectionFactory extends Factory
{
    protected $model = Connection::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'status' => true,
        ];
    }
}
