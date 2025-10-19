<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

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
            'username' => 'required|string|alpha_dash:ascii|min:3|max:32|unique:users,username',
            'email'    => 'required|email:rfc,dns,spoof,filter|lowercase|unique:users,email',
            'password' => [
                'required','string','confirmed',
                Password::min(8)->letters()->numbers()->uncompromised(),
            ],
            'password_confirmation' => 'required_with:password|string|min:8',
        ];
    }
}
