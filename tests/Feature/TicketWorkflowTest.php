<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function regular_user_can_create_ticket_and_defaults_to_open()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/tickets', [
            'category' => 'IT_SUPPORT',
            'title' => 'My printer is broken',
            'description' => 'It will not print',
            'priority' => 'HIGH',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', Ticket::STATUS_OPEN);

        $this->assertDatabaseHas('tickets', [
            'requester_id' => $user->id,
            'status' => Ticket::STATUS_OPEN,
        ]);
    }

    /** @test */
    public function technician_can_update_status_and_resolved_at_is_set()
    {
        $tech = User::factory()->create();
        $tech->assignRole('Teknisi');

        $ticket = Ticket::factory()->create(['status' => Ticket::STATUS_OPEN]);

        $this->actingAs($tech);
        $response = $this->patchJson("/api/tickets/{$ticket->id}", [
            'status' => Ticket::STATUS_SOLVED,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', Ticket::STATUS_SOLVED);

        $this->assertNotNull($ticket->fresh()->resolved_at);
    }

    /** @test */
    public function regular_user_cannot_change_status_via_api()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create(['status' => Ticket::STATUS_OPEN]);
        $this->actingAs($user);

        $this->patchJson("/api/tickets/{$ticket->id}", [
            'status' => Ticket::STATUS_SOLVED,
        ])->assertStatus(403);
    }

    /** @test */
    public function adding_comment_with_status_changes_ticket()
    {
        $tech = User::factory()->create();
        $tech->assignRole('Teknisi');
        $ticket = Ticket::factory()->create(['status' => Ticket::STATUS_ASSIGNED_DETECT]);

        $this->actingAs($tech);
        $this->postJson("/api/tickets/{$ticket->id}/comments", [
            'message' => 'Investigated and fixed',
            'status' => Ticket::STATUS_SOLVED_WITH_NOTES,
        ])->assertStatus(201);

        $this->assertEquals(Ticket::STATUS_SOLVED_WITH_NOTES, $ticket->fresh()->status);
    }

    /** @test */
    public function api_index_can_be_filtered_by_status()
    {
        $user = User::factory()->create();
        $user->assignRole('Teknisi');
        Ticket::factory()->count(3)->create(['status' => Ticket::STATUS_OPEN]);
        Ticket::factory()->count(2)->create(['status' => Ticket::STATUS_SOLVED]);

        $this->actingAs($user);
        $res = $this->getJson('/api/tickets?status=' . Ticket::STATUS_SOLVED);
        $res->assertStatus(200);
        $data = $res->json('data');
        $this->assertCount(2, $data);
    }

    /** @test */
    public function dashboard_summary_reflects_new_status_counts()
    {
        $user = User::factory()->create();
        $user->assignRole('Teknisi');
        Ticket::factory()->create(['status' => Ticket::STATUS_OPEN]);
        Ticket::factory()->create(['status' => Ticket::STATUS_ASSIGNED_DETECT]);
        Ticket::factory()->create(['status' => Ticket::STATUS_SOLVED_WITH_NOTES]);

        $this->actingAs($user);
        $res = $this->getJson('/api/dashboard/summary');
        $res->assertStatus(200)
            ->assertJson([ 'tickets' => [
                'open' => 1,
                'assigned_detect' => 1,
                'solved_with_notes' => 1,
            ]]);
    }

    /** @test */
    public function technician_can_upload_attachment_to_ticket()
    {
        $tech = User::factory()->create();
        $tech->assignRole('Teknisi');
        $ticket = Ticket::factory()->create();
        $this->actingAs($tech);
        \Illuminate\Support\Facades\Storage::fake('local');

        $file = \Illuminate\Http\UploadedFile::fake()->create('log.txt', 100);
        $response = $this->postJson("/api/tickets/{$ticket->id}/attachments", [
            'file' => $file,
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('attachments', [
            'ticket_id' => $ticket->id,
            'file_name' => 'log.txt',
        ]);

        // fetch ticket and verify attachments are included
        $res2 = $this->getJson("/api/tickets/{$ticket->id}");
        $res2->assertStatus(200);
        $this->assertArrayHasKey('attachments', $res2->json());
    }

    /** @test */
    public function can_attach_file_when_commenting_via_api()
    {
        $tech = User::factory()->create();
        $tech->assignRole('Teknisi');
        $ticket = Ticket::factory()->create();
        $this->actingAs($tech);
        \Illuminate\Support\Facades\Storage::fake('local');

        $file = \Illuminate\Http\UploadedFile::fake()->create('note.pdf', 200);
        $this->postJson("/api/tickets/{$ticket->id}/comments", [
            'message' => 'See attached',
            'attachment' => $file,
        ])->assertStatus(201);

        $this->assertDatabaseHas('attachments', [
            'ticket_id' => $ticket->id,
            'file_name' => 'note.pdf',
        ]);
    }

    /** @test */
    public function web_comment_uploads_attachment()
    {
        $tech = User::factory()->create();
        $tech->assignRole('Teknisi');
        $ticket = Ticket::factory()->create();
        $this->actingAs($tech);
        \Illuminate\Support\Facades\Storage::fake('local');

        $file = \Illuminate\Http\UploadedFile::fake()->create('screenshot.png', 300);
        $response = $this->post("/tickets/{$ticket->id}/comments", [
            'message' => 'Attached screenshot',
            'attachment' => $file,
        ]);
        $response->assertRedirect();

        $this->assertDatabaseHas('attachments', [
            'ticket_id' => $ticket->id,
            'file_name' => 'screenshot.png',
        ]);
    }
}
