<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_emails()
    {
        Email::factory()->count(3)->create();

        $response = $this->getJson('/api/emails');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_email()
    {
        $user = User::factory()->create();
        $emailData = [
            'user_id' => $user->id,
            'email' => 'test@example.com'
        ];

        $response = $this->postJson('/api/emails', $emailData);

        $response->assertStatus(201)
            ->assertJsonFragment($emailData);

        $this->assertDatabaseHas('emails', $emailData);
    }

    public function test_cannot_create_email_with_invalid_data()
    {
        $response = $this->postJson('/api/emails', [
            'user_id' => 999,
            'email' => 'invalid-email'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id', 'email']);
    }

    public function test_cannot_create_duplicate_email()
    {
        $user = User::factory()->create();
        $email = Email::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson('/api/emails', [
            'user_id' => $user->id,
            'email' => $email->email
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_can_get_single_email()
    {
        $email = Email::factory()->create();

        $response = $this->getJson("/api/emails/{$email->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'email' => $email->email,
                'user_id' => $email->user_id
            ]);
    }

    public function test_can_update_email()
    {
        $email = Email::factory()->create();
        $updateData = [
            'user_id' => $email->user_id,
            'email' => 'updated@example.com'
        ];

        $response = $this->putJson("/api/emails/{$email->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('emails', $updateData);
    }

    public function test_can_delete_email()
    {
        $email = Email::factory()->create();

        $response = $this->deleteJson("/api/emails/{$email->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('emails', ['id' => $email->id]);
    }

    public function test_returns_404_for_nonexistent_email()
    {
        $response = $this->getJson('/api/emails/999');

        $response->assertStatus(404);
    }
} 