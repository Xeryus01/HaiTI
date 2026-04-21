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
                'phone_number' => '+62812345678',
                'remember_token' => Str::random(10),
            ]
        );
        $admin->syncRoles(['Admin']);

        // Create Teknisi users (Technicians)
        $technicians = [
            [
                'name' => 'Fadil Rahman',
                'email' => 'fadil@example.com',
                'phone_number' => '+62812111111',
            ],
            [
                'name' => 'Marko Santoso',
                'email' => 'marko@example.com',
                'phone_number' => '+62812222222',
            ],
            [
                'name' => 'Eji Wijaya',
                'email' => 'eji@example.com',
                'phone_number' => '+62812333333',
            ],
            [
                'name' => 'Mesra Putri',
                'email' => 'mesra@example.com',
                'phone_number' => '+62812444444',
            ],
        ];

        foreach ($technicians as $techData) {
            $user = User::firstOrCreate(
                ['email' => $techData['email']],
                [
                    'name' => $techData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'phone_number' => $techData['phone_number'],
                    'remember_token' => Str::random(10),
                ]
            );
            $user->syncRoles(['Teknisi']);
        }

        // Create regular Users (Requesters)
        $regularUsers = [
            [
                'name' => 'Ahmad Surya',
                'email' => 'ahmad.surya@example.com',
                'phone_number' => '+62812555555',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@example.com',
                'phone_number' => '+62812666666',
            ],
            [
                'name' => 'Budi Hartono',
                'email' => 'budi.hartono@example.com',
                'phone_number' => '+62812777777',
            ],
            [
                'name' => 'Ratna Dewi',
                'email' => 'ratna.dewi@example.com',
                'phone_number' => '+62812888888',
            ],
            [
                'name' => 'Handri Pranoto',
                'email' => 'handri.pranoto@example.com',
                'phone_number' => '+62812999999',
            ],
            [
                'name' => 'Dina Kusuma',
                'email' => 'dina.kusuma@example.com',
                'phone_number' => '+62813111111',
            ],
        ];

        foreach ($regularUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'phone_number' => $userData['phone_number'],
                    'remember_token' => Str::random(10),
                ]
            );
            $user->syncRoles(['User']);
        }

        // Create test user for development
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'phone_number' => '+62812000000',
                'remember_token' => Str::random(10),
            ]
        );
        $testUser->syncRoles(['User']);
    }
}
