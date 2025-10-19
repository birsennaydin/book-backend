<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|min:3|max:32|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * lang/en/validation.php
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'email.required' => 'Email address is required.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }
}
