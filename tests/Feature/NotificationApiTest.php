<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_api_routes_are_accessible_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        auth()->login($user);

        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type' => 'info',
            'title' => 'Test Notification',
            'message' => 'Test notification message',
            'is_read' => false,
        ]);

        $this->getJson('/api/notifications/unread-count')
            ->assertStatus(200)
            ->assertJson(['success' => true, 'unread_count' => 1]);

        $this->getJson('/api/notifications/latest-unread')
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->getJson('/api/notifications')
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
