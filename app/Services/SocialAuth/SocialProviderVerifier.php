<?php

namespace App\Services\SocialAuth;

interface SocialProviderVerifier
{
    /**
     * @return array{
     *   provider: string,
     *   provider_user_id: string,     // sub/id
     *   email?: string|null,
     *   email_verified?: bool,
     *   name?: string|null,
     *   avatar_url?: string|null
     * }
     *
     * Throw \RuntimeException on invalid token.
     */
    public function verify(array $payload): array;
}
