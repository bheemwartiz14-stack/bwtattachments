<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Term;
use App\Models\User;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', env('ADMIN_EMAIL', 'admin@example.com'))->first();

        if (!$admin) {
            return;
        }

        $terms = [
            [
                'title' => 'Return & Refund Policy',
                'description' => 'Terms regarding product returns, exchanges, and refunds.',
                'is_active' => true,
            ],
        ];

        foreach ($terms as $term) {
            Term::updateOrCreate(
                ['title' => $term['title']],
                [
                    'description' => $term['description'],
                    'is_active' => $term['is_active'],
                    'created_by' => $admin->id,
                ]
            );
        }
    }
}
