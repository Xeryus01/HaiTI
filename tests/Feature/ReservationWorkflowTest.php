<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function technician_can_follow_up_zoom_request_and_add_zoom_link(): void
    {
        $user = User::factory()->create();
        $tech = User::factory()->create();
        $tech->assignRole('Teknisi');

        $reservation = Reservation::create([
            'code' => 'RES-TEST-001',
            'requester_id' => $user->id,
            'room_name' => 'Rapat Koordinasi',
            'purpose' => 'Permintaan ruang Zoom untuk rapat mingguan',
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHour(),
            'status' => 'PENDING',
        ]);

        $this->actingAs($tech)
            ->patch(route('reservations.update', $reservation), [
                'status' => 'APPROVED',
                'zoom_link' => 'https://zoom.us/j/123456789',
                'notes' => 'Silakan gunakan link Zoom ini 10 menit sebelum rapat dimulai.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'APPROVED',
            'zoom_link' => 'https://zoom.us/j/123456789',
        ]);
    }

    /** @test */
    public function user_can_export_ticket_and_zoom_request_data(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ticketExport = $this->get(route('exports.tickets'));
        $ticketExport->assertOk();
        $ticketExport->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $reservationExport = $this->get(route('exports.reservations'));
        $reservationExport->assertOk();
        $reservationExport->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
