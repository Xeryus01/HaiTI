<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );
        $admin->syncRoles(['Admin']);

        // Create Teknisi users
        $technicians = [
            [
                'name' => 'Fadil Rahman',
                'email' => 'fadil@example.com',
            ],
            [
                'name' => 'Marko Santoso',
                'email' => 'marko@example.com',
            ],
            [
                'name' => 'Eji Wijaya',
                'email' => 'eji@example.com',
            ],
            [
                'name' => 'Mesra Putri',
                'email' => 'mesra@example.com',
            ],
        ];

        foreach ($technicians as $techData) {
            $user = User::firstOrCreate(
                ['email' => $techData['email']],
                [
                    'name' => $techData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
            $user->syncRoles(['Teknisi']);
        }

        // Create Test User
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );
        $testUser->syncRoles(['User']);
    }
}
