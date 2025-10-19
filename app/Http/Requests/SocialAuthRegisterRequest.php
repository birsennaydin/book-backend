<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialAuthRegisterRequest extends FormRequest
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
            'provider' => ['required', 'string', Rule::in(['google','facebook','apple','tiktok'])],
            'provider_user_id' => [
                'required',
                'string',
                Rule::unique('social_accounts')->where(fn ($query) =>
                $query->where('provider', $this->provider)
                ),
            ],
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|min:3|max:32|unique:users,username',
        ];
    }

    public function messages(): array
    {
        return [
            'provider.required' => 'Provider is required.',
            'provider.in' => 'Provider must be one of: google, facebook, apple, tiktok.',
            'provider_user_id.required' => 'Provider user ID is required.',
            'provider_user_id.unique' => 'This social account already exists for this provider.',
            'email.required' => 'Email is required.',
            'email.unique' => 'This email is already taken.',
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
        ];
    }
}
