<?php

namespace App\Services\SocialAuth;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class GoogleVerifier implements SocialProviderVerifier
{
    public function verify(array $payload): array
    {
        $idToken = $payload['id_token'] ?? null;
        if (!$idToken) {
            throw new RuntimeException('Missing id_token');
        }

        // 1) Fetch and cache Google JWKS (24 hours)
        $jwksJson = Cache::remember('google_jwks', 60 * 24, function () {
            $res = Http::timeout(5)->get('https://www.googleapis.com/oauth2/v3/certs');
            if (!$res->ok()) {
                throw new RuntimeException('Cannot fetch Google JWKS');
            }
            return $res->json(); // Must include "keys" array
        });

        // 2) Convert JWK to Key set (algorithm: RS256)
        $keySet = JWK::parseKeySet($jwksJson, 'RS256');

        try {
            // Optional: allow small clock skew between Google and server
            JWT::$leeway = 60;

            // 3) Decode and verify JWT signature (no 3rd parameter in v6)
            $decoded = JWT::decode($idToken, $keySet);
        } catch (Throwable $e) {
            // Catch invalid signature, expired token, etc.
            throw new RuntimeException('Invalid Google token: '.$e->getMessage());
        }

        // 4) Verify the token issuer
        $iss = $decoded->iss ?? '';
        if (!in_array($iss, ['https://accounts.google.com', 'accounts.google.com'], true)) {
            throw new RuntimeException('Invalid iss');
        }

        // 5) Verify the audience (should match your Google client_id)
        $aud = $decoded->aud ?? null;
        $clientId = config('services.google.client_id');
        //dd($clientId);
        $audOk = is_array($aud)
            ? in_array($clientId, $aud, true)
            : ($aud === $clientId);
        //dd($clientId, $aud, $audOk);

        if (!$audOk) {
            throw new RuntimeException('Invalid aud');
        }

        // 6) Return normalized user info
        return [
            'provider'         => 'google',
            'provider_user_id' => (string)($decoded->sub ?? ''),
            'email'            => isset($decoded->email) ? (string)$decoded->email : null,
            'email_verified'   => (bool)($decoded->email_verified ?? false),
            'name'             => isset($decoded->name) ? (string)$decoded->name : null,
            'avatar_url'       => isset($decoded->picture) ? (string)$decoded->picture : null,
        ];
    }
}
