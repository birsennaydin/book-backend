<?php

namespace App\Services\SocialAuth;

use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

class SocialVerifierFactory
{
    public function __construct(private readonly Container $app) {}

    public function make(string $provider): SocialProviderVerifier
    {
        return match ($provider) {
            'google'   => $this->app->make(GoogleVerifier::class),
            'apple'    => $this->app->make(AppleVerifier::class),
            'facebook' => $this->app->make(FacebookVerifier::class),
            'tiktok'   => $this->app->make(TikTokVerifier::class),
            default    => throw new InvalidArgumentException("Unsupported provider: {$provider}"),
        };
    }
}
