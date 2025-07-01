<?php

namespace Tests\Unit;

use App\Application\Services\EmailService;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Models\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EmailService $emailService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emailService = new EmailService();
    }

    public function test_send_welcome_email_to_all_user_emails()
    {
        Mail::fake();

        $user = User::factory()->create([
            'first_name' => 'Jan',
            'last_name' => 'Kowalski'
        ]);

        Email::factory()->count(3)->create(['user_id' => $user->id]);

        // Debug: sprawdź czy e-maile są poprawnie przypisane
        $userEmails = $user->emails()->pluck('email');
        $this->assertCount(3, $userEmails);

        $this->emailService->sendWelcomeEmail($user);

        Mail::assertSent(WelcomeMail::class, 3);
    }

    public function test_send_welcome_email_with_correct_message()
    {
        Mail::fake();

        $user = User::factory()->create([
            'first_name' => 'Anna',
            'last_name' => 'Nowak'
        ]);

        Email::factory()->create(['user_id' => $user->id]);

        $this->emailService->sendWelcomeEmail($user);

        Mail::assertSent(WelcomeMail::class, 1);
    }

    public function test_send_welcome_email_to_user_without_emails()
    {
        Mail::fake();

        $user = User::factory()->create();

        $this->emailService->sendWelcomeEmail($user);

        Mail::assertNotSent(WelcomeMail::class);
    }

    public function test_send_welcome_email_to_user_with_single_email()
    {
        Mail::fake();

        $user = User::factory()->create([
            'first_name' => 'Piotr',
            'last_name' => 'Wiśniewski'
        ]);

        $email = Email::factory()->create(['user_id' => $user->id]);

        $this->emailService->sendWelcomeEmail($user);

        Mail::assertSent(WelcomeMail::class, 1);
    }
} 