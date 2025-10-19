<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles per guard (web: admin, writer; api: admin, writer, reader)
        $map = [
            'web' => ['admin','writer'],
            'api' => ['admin','writer','reader'],
        ];

        foreach ($map as $guard => $roles) {
            foreach ($roles as $name) {
                Role::firstOrCreate(['name' => $name, 'guard_name' => $guard]);
            }
        }

        // Sample users
        $admin  = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin',  'password' => Hash::make('Admin123!')]
        );
        $writer = User::firstOrCreate(
            ['email' => 'writer@example.com'],
            ['name' => 'Writer', 'password' => Hash::make('Writer123!')]
        );
        $reader = User::firstOrCreate(
            ['email' => 'reader@example.com'],
            ['name' => 'Reader', 'password' => Hash::make('Reader123!')]
        );

        // Assign roles as Role objects (avoid guard mismatch)
        $admin->syncRoles([
            Role::findByName('admin','web'),
            Role::findByName('admin','api'),
        ]);
        $writer->syncRoles([
            Role::findByName('writer','web'),
            Role::findByName('writer','api'),
        ]);
        $reader->syncRoles([
            Role::findByName('reader','api'),
        ]);
    }
}
