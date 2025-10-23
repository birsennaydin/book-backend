<?php

namespace App\Providers;

use App\Services\SocialAuth\SocialVerifierFactory;
use Illuminate\Support\ServiceProvider;

class SocialAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SocialVerifierFactory::class, fn($app) => new SocialVerifierFactory($app));
    }

    public function provides(): array
    {
        return [SocialVerifierFactory::class];
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
