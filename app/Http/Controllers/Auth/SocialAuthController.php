<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocialAuthController extends Controller
{
    public function redirect(string $provider)
    {
        // TODO: Socialite::driver($provider)->redirect();
        return response("Redirect to {$provider} (wire soon)", 200);
    }

    public function callback(string $provider, Request $request)
    {
        // TODO: $user = Socialite::driver($provider)->user();
        return redirect()->route('dashboard');
    }
}
