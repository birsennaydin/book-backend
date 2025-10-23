<?php

namespace App\Services\SocialAuth;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class FacebookVerifier implements SocialProviderVerifier
{
    public function verify(array $payload): array
    {
        $accessToken = $payload['access_token'] ?? null;
        if (! $accessToken) throw new RuntimeException('Missing access_token');

        $appId = config('services.facebook.client_id');
        $appSecret = config('services.facebook.client_secret');
        $appToken = $appId . '|' . $appSecret;

        $debug = Http::timeout(5)->get('https://graph.facebook.com/debug_token', [
            'input_token' => $accessToken,
            'access_token'=> $appToken,
        ])->json('data');

        if (!($debug['is_valid'] ?? false)) {
            throw new RuntimeException('Invalid Facebook token');
        }
        if (($debug['app_id'] ?? '') !== $appId) {
            throw new RuntimeException('Token audience mismatch');
        }

        $me = Http::timeout(5)->get('https://graph.facebook.com/v17.0/me', [
            'fields'       => 'id,name,email,picture',
            'access_token' => $accessToken,
        ])->json();

        return [
            'provider'         => 'facebook',
            'provider_user_id' => (string)($me['id'] ?? ''),
            'email'            => $me['email'] ?? null,
            'email_verified'   => true, // Facebook provide the verified email
            'name'             => $me['name'] ?? null,
            'avatar_url'       => $me['picture']['data']['url'] ?? null,
        ];
    }
}
