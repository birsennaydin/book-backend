<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SocialAuthLoginRequest extends FormRequest
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
            'provider'         => 'required|string|in:google,facebook,apple,tiktok',
            'provider_user_id' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'provider.required'         => 'Provider is required.',
            'provider.in'               => 'Provider must be one of: google, facebook, apple, tiktok.',
            'provider_user_id.required' => 'Provider user ID is required.',
        ];
    }
}
