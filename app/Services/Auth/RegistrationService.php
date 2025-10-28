<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

class RegistrationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'username' => trim($data['username']),
                'email'    => strtolower(trim($data['email'])),
                'password' => $data['password'],
            ]);

            // Assign web role(s)
            $user->assignRole('writer'); // guard=web

            // Assign api role(s) without guard mismatch
            $originalGuard = $user->guard_name ?? 'web';
            $user->guard_name = 'api';
            $user->assignRole('reader');
            $user->guard_name = $originalGuard;

            event(new Registered($user));

            return $user;
        });
    }
}
