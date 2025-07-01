<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $emailRule = ['required', 'email', 'max:255'];
        if ($this->isMethod('post')) {
            $emailRule[] = Rule::unique('emails', 'email');
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            $emailRule[] = Rule::unique('emails', 'email')->ignore($this->route('email')->id ?? null);
        }
        return [
            'email' => $emailRule,
            'user_id' => ['required', 'exists:users,id'],
        ];
    }
}
