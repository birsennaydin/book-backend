<?php

namespace App\Providers;


use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', fn(Request $r) =>
        [ Limit::perMinute(5)->by(strtolower((string)$r->input('email')).'|'.$r->ip()) ]
        );

        RateLimiter::for('register', fn(Request $r) =>
        [ Limit::perMinutes(2, 3)->by(strtolower((string)$r->input('email')).'|'.$r->ip()) ]
        );

        RateLimiter::for('social', fn(Request $r) =>
        [ Limit::perMinute(6)->by(($r->input('provider').'|'.$r->input('provider_id').'|'.$r->ip())) ]
        );
    }
}
