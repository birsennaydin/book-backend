<?php

namespace App\Services\SocialAuth;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class TikTokVerifier implements SocialProviderVerifier
{
    public function verify(array $payload): array
    {
        $accessToken = $payload['access_token'] ?? null;
        if (! $accessToken) throw new RuntimeException('Missing access_token');

        $res = Http::timeout(5)->withToken($accessToken)
            ->get('https://open.tiktokapis.com/v2/user/info/')->json();

        $openId = data_get($res, 'data.user.open_id');
        if (!$openId) throw new RuntimeException('Invalid TikTok token');

        return [
            'provider'         => 'tiktok',
            'provider_user_id' => (string)$openId,
            'email'            => null,            // generally null
            'email_verified'   => false,           // If there is not verificated email
            'name'             => data_get($res, 'data.user.display_name'),
            'avatar_url'       => data_get($res, 'data.user.avatar_url'),
        ];
    }
}

