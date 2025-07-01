<?php

namespace Tests\Feature;

use App\Http\Requests\EmailRequest;
use App\Models\User;
use App\Models\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_email_data_passes_validation()
    {
        $user = User::factory()->create();
        
        $request = new EmailRequest();
        $request->merge([
            'user_id' => $user->id,
            'email' => 'test@example.com'
        ]);

        $validator = validator($request->all(), $request->rules());
        
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_email_data_fails_validation()
    {
        $request = new EmailRequest();
        $request->merge([
            'user_id' => 999,
            'email' => 'invalid-email'
        ]);

        $validator = validator($request->all(), $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('user_id', $validator->errors()->toArray());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_user_id_is_required()
    {
        $request = new EmailRequest();
        $request->merge([
            'email' => 'test@example.com'
        ]);

        $validator = validator($request->all(), $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('user_id', $validator->errors()->toArray());
    }

    public function test_email_is_required()
    {
        $user = User::factory()->create();
        
        $request = new EmailRequest();
        $request->merge([
            'user_id' => $user->id
        ]);

        $validator = validator($request->all(), $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_user_id_must_exist()
    {
        $request = new EmailRequest();
        $request->merge([
            'user_id' => 999,
            'email' => 'test@example.com'
        ]);

        $validator = validator($request->all(), $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('user_id', $validator->errors()->toArray());
    }

    public function test_email_must_be_valid_format()
    {
        $user = User::factory()->create();
        
        $request = new EmailRequest();
        $request->merge([
            'user_id' => $user->id,
            'email' => 'invalid-email-format'
        ]);

        $validator = validator($request->all(), $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_email_must_be_unique()
    {
        $user = User::factory()->create();
        $existingEmail = Email::factory()->create(['user_id' => $user->id]);
        
        $request = new EmailRequest();
        $request->merge([
            'user_id' => $user->id,
            'email' => $existingEmail->email
        ]);
        $request->setMethod('POST');

        $validator = validator($request->all(), $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_email_has_max_length_validation()
    {
        $user = User::factory()->create();
        
        $request = new EmailRequest();
        $request->merge([
            'user_id' => $user->id,
            'email' => str_repeat('a', 250) . '@example.com'
        ]);

        $validator = validator($request->all(), $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }
} 