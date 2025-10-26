<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset Spatie cache to avoid stale lookups
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Web GUI roles
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('editor', 'web');
        Role::findOrCreate('writer', 'web');

        // API (Flutter) roles
        Role::findOrCreate('reader', 'api');

        // Fetch roles with guard to avoid null and guard mismatch
        $admin  = Role::where('name','admin')->where('guard_name','web')->firstOrFail();
        $editor = Role::where('name','editor')->where('guard_name','web')->firstOrFail();
        $writer = Role::where('name','writer')->where('guard_name','web')->firstOrFail();
        $reader = Role::where('name','reader')->where('guard_name','api')->firstOrFail();

        // Collect PERMISSION NAMES by guard
        $webPermNames = Permission::where('guard_name','web')->pluck('name')->all();
        $apiPermNames = Permission::where('guard_name','api')->pluck('name')->all();

        $writerPerms = [
            'home-permission',
            'my-stories-permission',
            'income-permission',
            'task-center-permission',
            'inbox-permission',
        ];

        $editorPerms = [
            'home-permission',
            'my-stories-permission',
            'task-center-permission',
            'inbox-permission',
        ];

        // Admin gets ALL web permissions (do NOT pass Permission::all())
        $adminPerms = $webPermNames;

        // Reader gets API permissions intended for mobile
        $readerPerms = [
            'home-permission',
            'library-permission',
            'profile-permission',
        ];

        // Sync ensures deterministic state and avoids duplicate pivot entries
        $writer->syncPermissions($writerPerms);
        $editor->syncPermissions($editorPerms);
        $admin->syncPermissions($adminPerms);

        $reader->syncPermissions($readerPerms);

        // Reset cache again after mutations
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
