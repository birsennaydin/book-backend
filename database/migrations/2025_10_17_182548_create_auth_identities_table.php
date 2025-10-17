<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auth_identities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete(); // Delete identities when user is deleted

            $table->string('provider'); // e.g. google, apple, facebook, tiktok
            $table->string('provider_user_id'); // Unique user ID from the provider
            $table->string('provider_email')->nullable(); // Email returned by the provider
            $table->string('name_from_provider')->nullable(); // Display name from provider
            $table->string('avatar_from_provider')->nullable(); // Profile image from provider

            // Optional: Store tokens (encrypt before saving if sensitive)
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();

            $table->json('raw_profile')->nullable(); // Raw JSON response from provider
            $table->timestamp('last_used_at')->nullable();

            $table->timestamps();

            // Unique constraint to prevent duplicate identity entries
            $table->unique(['provider', 'provider_user_id']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_identities');
    }
};
