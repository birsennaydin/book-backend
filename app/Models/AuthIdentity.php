<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthIdentity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'provider_email',
        'name_from_provider',
        'avatar_from_provider',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'raw_profile',
        'last_used_at',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'last_used_at' => 'datetime',
        'raw_profile' => 'array',
    ];

    /**
     * Relationship: each identity belongs to a single user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
