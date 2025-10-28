<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => fn () => $request->user()
                    ? $request->user()->only(['id','username','email'])
                    : null,
                'roles' => fn () => $request->user()
                    ? $request->user()->getRoleNames() // ['admin', 'writer', ...]
                    : [],
                'permissions' => fn () => $request->user()
                    ? $request->user()->getAllPermissions()->pluck('name') // ['inbox-permission', ...]
                    : [],
            ],
            'flash' => [
                'status' => fn () => session('status'),
            ],
        ]);
    }
}
