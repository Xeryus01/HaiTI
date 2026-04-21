<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Roles and permissions first
            RoleSeeder::class,
            PermissionSeeder::class,
            
            // Core data
            UserSeeder::class,
            AssetSeeder::class,
            
            // Main entities
            TicketSeeder::class,
            ReservationSeeder::class,
            
            // Supporting data
            TicketCommentSeeder::class,
            AttachmentSeeder::class,
            
            // Scheduling and sequences
            PiketScheduleSeeder::class,
            CodeSequenceSeeder::class,
            
            // Notifications and logs
            NotificationSeeder::class,
            LogSeeder::class,
        ]);
    }
}

