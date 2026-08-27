<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_sees_only_own_notifications(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $other = User::factory()->create(['email_verified_at' => now()]);
        Notification::create(['user_id' => $user->id, 'title' => 'Própria', 'message' => 'Mensagem própria']);
        Notification::create(['user_id' => $other->id, 'title' => 'Privada', 'message' => 'Mensagem privada']);

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['title' => 'Própria'])
            ->assertJsonMissing(['title' => 'Privada']);
    }

    public function test_user_can_mark_own_notification_as_read(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $notification = Notification::create(['user_id' => $user->id, 'title' => 'Aviso', 'message' => 'Mensagem']);

        $this->actingAs($user)
            ->post(route('notifications.read', $notification))
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertNotNull($notification->refresh()->read_at);
    }

    public function test_user_cannot_mark_another_users_notification_as_read(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $other = User::factory()->create(['email_verified_at' => now()]);
        $notification = Notification::create(['user_id' => $other->id, 'title' => 'Privada', 'message' => 'Mensagem']);

        $this->actingAs($user)
            ->post(route('notifications.read', $notification))
            ->assertForbidden();

        $this->assertNull($notification->refresh()->read_at);
    }

    public function test_user_can_read_unread_count_and_mark_all_as_read(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        Notification::create(['user_id' => $user->id, 'title' => 'Um', 'message' => 'Mensagem']);
        Notification::create(['user_id' => $user->id, 'title' => 'Dois', 'message' => 'Mensagem']);
        Notification::create(['user_id' => $user->id, 'title' => 'Lido', 'message' => 'Mensagem', 'read_at' => now()]);

        $this->actingAs($user)
            ->get(route('notifications.unread-count'))
            ->assertOk()
            ->assertJson(['count' => 2]);

        $this->actingAs($user)
            ->post(route('notifications.read-all'))
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseCount('notifications', 3);
        $this->assertDatabaseMissing('notifications', ['user_id' => $user->id, 'read_at' => null]);
    }
}
