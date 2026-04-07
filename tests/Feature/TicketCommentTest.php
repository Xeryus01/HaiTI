<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketComment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_comment_on_own_ticket(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create(['requester_id' => $user->id]);

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/tickets/{$ticket->id}/comments", [
                'message' => 'This is a test comment',
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('ticket_comments', [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => 'This is a test comment',
            'is_internal' => false,
        ]);
    }

    public function test_admin_can_comment_on_any_ticket(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $user = User::factory()->create();
        $ticket = Ticket::factory()->create(['requester_id' => $user->id]);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/tickets/{$ticket->id}/comments", [
                'message' => 'Admin comment',
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('ticket_comments', [
            'ticket_id' => $ticket->id,
            'user_id' => $admin->id,
            'message' => 'Admin comment',
            'is_internal' => false,
        ]);
    }

    public function test_technician_can_comment_on_assigned_ticket(): void
    {
        $technician = User::factory()->create();
        $technician->assignRole('Teknisi');

        $user = User::factory()->create();
        $ticket = Ticket::factory()->create([
            'requester_id' => $user->id,
            'assignee_id' => $technician->id
        ]);

        $this->actingAs($technician, 'sanctum')
            ->postJson("/api/tickets/{$ticket->id}/comments", [
                'message' => 'Technician comment',
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('ticket_comments', [
            'ticket_id' => $ticket->id,
            'user_id' => $technician->id,
            'message' => 'Technician comment',
            'is_internal' => false,
        ]);
    }

    public function test_user_cannot_comment_on_others_ticket(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $ticket = Ticket::factory()->create(['requester_id' => $user1->id]);

        $this->actingAs($user2, 'sanctum')
            ->postJson("/api/tickets/{$ticket->id}/comments", [
                'message' => 'Unauthorized comment',
            ])
            ->assertStatus(403);

        $this->assertDatabaseMissing('ticket_comments', [
            'ticket_id' => $ticket->id,
            'user_id' => $user2->id,
            'message' => 'Unauthorized comment',
        ]);
    }
}
