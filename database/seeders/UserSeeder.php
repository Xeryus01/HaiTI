<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('Admin');

        // Create Teknisi users
        $technicians = [
            [
                'name' => 'Fadil',
                'email' => 'fadil@example.com',
            ],
            [
                'name' => 'Marko',
                'email' => 'marko@example.com',
            ],
            [
                'name' => 'Eji',
                'email' => 'eji@example.com',
            ],
            [
                'name' => 'Mesra',
                'email' => 'mesra@example.com',
            ],
        ];

        foreach ($technicians as $techData) {
            $user = User::firstOrCreate(
                ['email' => $techData['email']],
                [
                    'name' => $techData['name'],
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole('Teknisi');
        }

        // Create Test User
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );
        $testUser->assignRole('User');
    }
}
