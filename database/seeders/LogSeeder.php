<?php

namespace Database\Seeders;

use App\Models\Log;
use App\Models\Ticket;
use App\Models\Reservation;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Seeder;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $tickets = Ticket::all();
        $reservations = Reservation::all();
        $assets = Asset::all();

        if ($users->isEmpty()) {
            return;
        }

        // Ticket creation logs
        foreach ($tickets->take(6) as $ticket) {
            Log::create([
                'actor_id' => $ticket->requester_id,
                'entity_type' => 'Ticket',
                'entity_id' => $ticket->id,
                'action' => 'created',
                'meta' => [
                    'title' => $ticket->title,
                    'category' => $ticket->category,
                    'priority' => $ticket->priority,
                ],
                'created_at' => $ticket->created_at,
            ]);
        }

        // Ticket assignment logs
        foreach ($tickets->where('assignee_id', '!=', null)->take(4) as $ticket) {
            Log::create([
                'actor_id' => $ticket->assignee_id,
                'entity_type' => 'Ticket',
                'entity_id' => $ticket->id,
                'action' => 'assigned',
                'meta' => [
                    'assigned_to' => $ticket->assignee->name,
                    'priority' => $ticket->priority,
                ],
                'created_at' => $ticket->updated_at->subDays(rand(1, 5)),
            ]);
        }

        // Ticket status change logs
        $statusChanges = [
            ['code' => 'TKT-2026-003', 'status' => 'SOLVED'],
            ['code' => 'TKT-2026-004', 'status' => 'SOLVED'],
            ['code' => 'TKT-2026-007', 'status' => 'SOLVED_WITH_NOTES'],
            ['code' => 'TKT-2026-009', 'status' => 'SOLVED'],
        ];

        foreach ($statusChanges as $change) {
            $ticket = Ticket::where('code', $change['code'])->first();
            $actor = $ticket->assignee_id ? User::find($ticket->assignee_id) : $users->random();

            if ($ticket && $actor) {
                Log::create([
                    'actor_id' => $actor->id,
                    'entity_type' => 'Ticket',
                    'entity_id' => $ticket->id,
                    'action' => 'status_changed',
                    'meta' => [
                        'from_status' => 'OPEN',
                        'to_status' => $change['status'],
                        'title' => $ticket->title,
                    ],
                    'created_at' => $ticket->updated_at,
                ]);
            }
        }

        // Reservation creation logs
        foreach ($reservations->take(5) as $reservation) {
            Log::create([
                'actor_id' => $reservation->requester_id,
                'entity_type' => 'Reservation',
                'entity_id' => $reservation->id,
                'action' => 'created',
                'meta' => [
                    'room_name' => $reservation->room_name,
                    'purpose' => $reservation->purpose,
                    'start_time' => $reservation->start_time->toDateTimeString(),
                ],
                'created_at' => $reservation->created_at,
            ]);
        }

        // Reservation approval logs
        foreach ($reservations->where('status', 'APPROVED')->take(4) as $reservation) {
            Log::create([
                'actor_id' => $reservation->approver_id ?? $users->random()->id,
                'entity_type' => 'Reservation',
                'entity_id' => $reservation->id,
                'action' => 'approved',
                'meta' => [
                    'room_name' => $reservation->room_name,
                    'notes' => $reservation->notes,
                ],
                'created_at' => $reservation->updated_at,
            ]);
        }

        // Asset update logs
        foreach ($assets->take(5) as $asset) {
            Log::create([
                'actor_id' => $users->random()->id,
                'entity_type' => 'Asset',
                'entity_id' => $asset->id,
                'action' => 'updated',
                'meta' => [
                    'asset_code' => $asset->asset_code,
                    'name' => $asset->name,
                    'condition' => $asset->condition,
                    'holder' => $asset->holder,
                ],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Admin activity logs
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            // User management
            Log::create([
                'actor_id' => $adminUser->id,
                'entity_type' => 'User',
                'entity_id' => $users->random()->id,
                'action' => 'role_assigned',
                'meta' => [
                    'role' => 'Teknisi',
                    'timestamp' => now()->subDays(10)->toDateTimeString(),
                ],
                'created_at' => now()->subDays(10),
            ]);

            // System configuration
            Log::create([
                'actor_id' => $adminUser->id,
                'entity_type' => 'System',
                'entity_id' => 1,
                'action' => 'configuration_updated',
                'meta' => [
                    'setting' => 'notification_settings',
                    'changes' => 'WhatsApp notifications enabled',
                ],
                'created_at' => now()->subDays(15),
            ]);

            // Backup execution
            Log::create([
                'actor_id' => $adminUser->id,
                'entity_type' => 'System',
                'entity_id' => 1,
                'action' => 'backup_completed',
                'meta' => [
                    'backup_size' => '45.2 GB',
                    'duration' => '2 hours 15 minutes',
                    'status' => 'success',
                ],
                'created_at' => now()->subDays(1)->setTime(23, 30),
            ]);
        }

        // Additional generic logs for activity history
        $actions = ['viewed', 'exported', 'printed', 'downloaded'];
        for ($i = 0; $i < 10; $i++) {
            $entityTypes = ['Ticket', 'Reservation', 'Asset', 'Report'];
            $entityType = $entityTypes[array_rand($entityTypes)];
            
            Log::create([
                'actor_id' => $users->random()->id,
                'entity_type' => $entityType,
                'entity_id' => rand(1, 20),
                'action' => $actions[array_rand($actions)],
                'meta' => [
                    'description' => $entityType . ' ' . $actions[array_rand($actions)],
                ],
                'created_at' => now()->subDays(rand(1, 30))->setTime(rand(8, 18), rand(0, 59)),
            ]);
        }
    }
}
