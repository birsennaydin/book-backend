<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * Only allow fillable fields that can safely be mass-assigned.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar_url',
        'locale',
        'timezone',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected array $guard_name = ['web', 'api'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Relationship: one user can have multiple social identities (Google, Apple, etc.)
     */
    public function identities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AuthIdentity::class);
    }

    /**
     * Helper: Check if user registered via a social provider.
     */
    public function isSocialAccount(): bool
    {
        return $this->identities()->exists() && empty($this->password);
    }

    /**
     * Helper: Return the preferred display name (username > name > email)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->username ?? $this->name ?? $this->email ?? 'Anonymous';
    }
}
