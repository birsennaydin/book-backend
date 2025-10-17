<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        $admin  = Role::firstOrCreate(['name' => 'admin']);
        $author = Role::firstOrCreate(['name' => 'author']);

        // Admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('Admin123!')]
        );
        $adminUser->syncRoles([$admin]);

        // Author user
        $authorUser = User::firstOrCreate(
            ['email' => 'author@example.com'],
            ['name' => 'Author One', 'password' => Hash::make('Author123!')]
        );
        $authorUser->syncRoles([$author]);
    }
}
