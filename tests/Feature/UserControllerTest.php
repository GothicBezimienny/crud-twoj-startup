<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_users()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_user()
    {
        $userData = [
            'first_name' => 'Jan',
            'last_name' => 'Kowalski',
            'phone_number' => '123456789'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJsonFragment($userData);

        $this->assertDatabaseHas('users', $userData);
    }

    public function test_cannot_create_user_with_invalid_data()
    {
        $response = $this->postJson('/api/users', [
            'first_name' => '',
            'last_name' => '',
            'phone_number' => ''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['first_name', 'last_name', 'phone_number']);
    }

    public function test_can_get_single_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone_number' => $user->phone_number
            ]);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create();
        $updateData = [
            'first_name' => 'Piotr',
            'last_name' => 'Nowak',
            'phone_number' => '987654321'
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('users', $updateData);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_can_send_welcome_email()
    {
        Mail::fake();
        
        $user = User::factory()->create();
        Email::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->postJson("/api/users/{$user->id}/send-welcome");

        $response->assertStatus(204);
        Mail::assertSent(\App\Mail\WelcomeMail::class, 2);
    }

    public function test_returns_404_for_nonexistent_user()
    {
        $response = $this->getJson('/api/users/999');

        $response->assertStatus(404);
    }
} 