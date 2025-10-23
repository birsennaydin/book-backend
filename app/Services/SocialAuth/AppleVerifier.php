<?php

namespace App\Services\SocialAuth;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class AppleVerifier implements SocialProviderVerifier
{
    public function verify(array $payload): array
    {
        $idToken = $payload['id_token'] ?? null;
        if (! $idToken) throw new RuntimeException('Missing id_token');

        $keys = Cache::remember('apple_jwks', 1440, function () {
            $res = Http::timeout(5)->get('https://appleid.apple.com/auth/keys');
            if (! $res->ok()) throw new RuntimeException('Cannot fetch Apple JWKS');
            return $res->json();
        });

        $decoded = JWT::decode($idToken, JWK::parseKeySet($keys), ['RS256']);
        $iss = $decoded->iss ?? '';
        if ($iss !== 'https://appleid.apple.com') throw new RuntimeException('Invalid iss');
        $aud = $decoded->aud ?? '';
        if (!in_array($aud, [config('services.apple.client_id')], true)) {
            throw new RuntimeException('Invalid aud');
        }

        return [
            'provider'         => 'apple',
            'provider_user_id' => (string)($decoded->sub ?? ''),
            'email'            => isset($decoded->email) ? (string)$decoded->email : null,
            'email_verified'   => ($decoded->email_verified ?? false) === 'true' || $decoded->email_verified === true,
        ];
    }
}
