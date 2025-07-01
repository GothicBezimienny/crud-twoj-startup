<?php

namespace App\Application\Services;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendWelcomeEmail(User $user): void
    {
        $emails = $user->emails()->pluck('email');
        $userName = $user->first_name . ' ' . $user->last_name;

        Log::info('Sending welcome emails', [
            'user_id' => $user->id,
            'user_name' => $userName,
            'emails_count' => $emails->count(),
            'emails' => $emails->toArray()
        ]);

        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new WelcomeMail($userName));
                Log::info('Welcome email sent successfully', ['email' => $email]);
            } catch (\Exception $e) {
                Log::error('Failed to send welcome email', [
                    'email' => $email,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}


