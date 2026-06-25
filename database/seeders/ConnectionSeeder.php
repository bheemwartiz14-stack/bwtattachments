<?php

namespace Database\Seeders;

use App\Models\Connection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ConnectionSeeder extends Seeder
{
    public function run(): void
    {
        $connections = ['Pin', 'Threaded', 'Weld-On', 'Quick Hitch', 'Universal'];

        foreach ($connections as $name) {
            Connection::firstOrCreate(
                ['name' => $name]
            );
        }
    }
}
