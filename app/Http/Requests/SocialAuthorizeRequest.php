<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialAuthorizeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Normalize optional inputs
        if ($this->has('email')) {
            $this->merge(['email' => trim((string) $this->input('email'))]);
        }
        if ($this->has('username')) {
            $this->merge(['username' => trim((string) $this->input('username'))]);
        }
    }

    public function rules(): array
    {
        return [
            'provider' => ['required','string', Rule::in(['google','apple','facebook','tiktok'])],

            // Token alanları: sağlayıcıya göre biri zorunlu
            // Google / Apple -> id_token; Facebook/TikTok -> access_token (server-side exchange/introspection yapacağız)
            'id_token'      => ['required_if:provider,google,apple','string'],
            'access_token'  => ['required_if:provider,facebook,tiktok','string'],

            // İlk kayıtta gerekebilir; varsa normalize ederiz (CITEXT var, lowercase gereksiz, trim yeter)
            'email'    => ['nullable','email'],
            'username' => ['nullable','string','min:3','max:32'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_token.required_if'     => 'id_token is required for Google/Apple.',
            'access_token.required_if' => 'access_token is required for Facebook/TikTok.',
        ];
    }
}
