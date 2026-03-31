<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Asset;
use App\Models\Ticket;
use App\Models\Reservation;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        // User::factory(10)->create();

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );
        $adminUser->syncRoles(['Admin']);

        $techUser = User::firstOrCreate(
            ['email' => 'teknisi@example.com'],
            [
                'name' => 'Teknisi IT',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );
        $techUser->syncRoles(['Teknisi']);

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

        // Create sample assets
        $assets = [
            [
                'asset_code' => 'AST-001',
                'name' => 'Dell Laptop',
                'type' => 'Computer',
                'status' => 'ACTIVE',
                'location' => 'Building A - Floor 1',
                'purchased_at' => now()->subMonths(6),
                'specs' => ['processor' => 'Intel i7', 'ram' => '16GB', 'storage' => '512GB SSD'],
            ],
            [
                'asset_code' => 'AST-002',
                'name' => 'HP Printer',
                'type' => 'Printer',
                'status' => 'ACTIVE',
                'location' => 'Building A - Floor 2',
                'purchased_at' => now()->subYear(),
                'specs' => ['model' => 'HP LaserJet Pro M404n', 'color' => 'Monochrome'],
            ],
            [
                'asset_code' => 'AST-003',
                'name' => 'Server - Main',
                'type' => 'Server',
                'status' => 'ACTIVE',
                'location' => 'Data Center',
                'purchased_at' => now()->subYears(2),
                'specs' => ['processor' => 'Dual Xeon', 'ram' => '128GB', 'storage' => '2TB'],
            ],
        ];

        foreach ($assets as $asset) {
            Asset::firstOrCreate(['asset_code' => $asset['asset_code']], $asset);
        }

        // Create sample tickets
        $tickets = [
            [
                'code' => 'TKT-2026-001',
                'title' => 'Printer not working',
                'description' => 'The printer on floor 2 is not printing documents',
                'category' => 'IT_SUPPORT',
                'status' => Ticket::STATUS_OPEN,
                'priority' => 'HIGH',
                'requester_id' => $testUser->id,
                'asset_id' => Asset::where('asset_code', 'AST-002')->first()->id ?? null,
            ],
            [
                'code' => 'TKT-2026-002',
                'title' => 'Email configuration issue',
                'description' => 'Need help configuring email client',
                'category' => 'IT_SUPPORT',
                'status' => Ticket::STATUS_ASSIGNED_DETECT,
                'priority' => 'MEDIUM',
                'requester_id' => $testUser->id,
            ],
            [
                'code' => 'TKT-2026-003',
                'title' => 'Server backup failed',
                'description' => 'Automated backup for main server failed last night',
                'category' => 'MAINTENANCE',
                'status' => Ticket::STATUS_OPEN,
                'priority' => 'CRITICAL',
                'requester_id' => $testUser->id,
            ],
            [
                'code' => 'TKT-2026-004',
                'title' => 'Software license renewal',
                'description' => 'Adobe Creative Suite license needs renewal',
                'category' => 'OTHER',
                'status' => Ticket::STATUS_SOLVED,
                'priority' => 'LOW',
                'requester_id' => $testUser->id,
                'assignee_id' => $techUser->id,
                'resolved_at' => now()->subDay(),
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::firstOrCreate(['code' => $ticket['code']], $ticket);
        }

        // Create sample reservations
        $reservations = [
            [
                'code' => 'RES-2603-001',
                'room_name' => 'Conference Room A',
                'purpose' => 'Team meeting',
                'start_time' => now()->addDays(1)->setHour(10)->setMinute(0),
                'end_time' => now()->addDays(1)->setHour(11)->setMinute(0),
                'status' => 'APPROVED',
                'requester_id' => $testUser->id,
            ],
            [
                'code' => 'RES-2603-002',
                'room_name' => 'Board Room',
                'purpose' => 'Quarterly review',
                'start_time' => now()->addDays(3)->setHour(14)->setMinute(0),
                'end_time' => now()->addDays(3)->setHour(16)->setMinute(0),
                'status' => 'PENDING',
                'requester_id' => $testUser->id,
            ],
            [
                'code' => 'RES-2603-003',
                'room_name' => 'Training Room',
                'purpose' => 'Staff training session',
                'start_time' => now()->addDays(5)->setHour(9)->setMinute(0),
                'end_time' => now()->addDays(5)->setHour(12)->setMinute(0),
                'status' => 'APPROVED',
                'requester_id' => $testUser->id,
            ]
        ];

        foreach ($reservations as $reservation) {
            Reservation::firstOrCreate(['code' => $reservation['code']], $reservation);
        }
    }
}

