<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Enable PostgreSQL CITEXT extension (case-insensitive text)
        DB::statement('CREATE EXTENSION IF NOT EXISTS citext;');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable(); // Email verification timestamp

            $table->string('password')->nullable(); // Nullable for social logins (Google, Apple, etc.)
            $table->string('avatar_url')->nullable(); // Profile picture URL

            $table->string('locale', 12)->default('tr'); // Language preference
            $table->string('timezone', 50)->default('Europe/Istanbul'); // User timezone

            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable(); // Last login IP (IPv4 or IPv6)

            $table->rememberToken(); // "Remember me" token for web sessions
            $table->softDeletes(); // Soft delete (keeps data for GDPR recovery) KVKK/GDPR
            $table->timestamps();

            // Common indexes for query optimization
            $table->index('last_login_at');
            $table->index('last_login_ip');
        });

        // Convert email column to CITEXT (case-insensitive)
        DB::statement('ALTER TABLE users ALTER COLUMN email TYPE citext;');

        // Password reset tokens table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Convert email column to CITEXT (case-insensitive)
        DB::statement('ALTER TABLE password_reset_tokens ALTER COLUMN email TYPE citext;');

        // Session tracking table (for web guard sessions)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
