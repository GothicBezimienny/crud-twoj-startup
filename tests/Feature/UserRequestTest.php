<?php

namespace Tests\Feature;

use App\Http\Requests\UserRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_user_data_passes_validation()
    {
        $request = new UserRequest();
        $request->merge([
            'first_name' => 'Jan',
            'last_name' => 'Kowalski',
            'phone_number' => '123456789'
        ]);

        $validator = validator($request->all(), $request->rules());
        
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_user_data_fails_validation()
    {
        $request = new UserRequest();
        $request->merge([
            'first_name' => '',
            'last_name' => '',
            'phone_number' => ''
        ]);

        $validator = validator($request->all(), $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('first_name', $validator->errors()->toArray());
        $this->assertArrayHasKey('last_name', $validator->errors()->toArray());
        $this->assertArrayHasKey('phone_number', $validator->errors()->toArray());
    }

    public function test_first_name_is_required()
    {
        $request = new UserRequest();
        $request->merge([
            'last_name' => 'Kowalski',
            'phone_number' => '123456789'
        ]);

        $validator = validator($request->all(), $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('first_name', $validator->errors()->toArray());
    }

    public function test_last_name_is_required()
    {
        $request = new UserRequest();
        $request->merge([
            'first_name' => 'Jan',
            'phone_number' => '123456789'
        ]);

        $validator = validator($request->all(), $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('last_name', $validator->errors()->toArray());
    }

    public function test_phone_number_is_required()
    {
        $request = new UserRequest();
        $request->merge([
            'first_name' => 'Jan',
            'last_name' => 'Kowalski'
        ]);

        $validator = validator($request->all(), $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone_number', $validator->errors()->toArray());
    }

    public function test_fields_have_max_length_validation()
    {
        $request = new UserRequest();
        $request->merge([
            'first_name' => str_repeat('a', 256),
            'last_name' => str_repeat('b', 256),
            'phone_number' => str_repeat('c', 256)
        ]);

        $validator = validator($request->all(), $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('first_name', $validator->errors()->toArray());
        $this->assertArrayHasKey('last_name', $validator->errors()->toArray());
        $this->assertArrayHasKey('phone_number', $validator->errors()->toArray());
    }
} 