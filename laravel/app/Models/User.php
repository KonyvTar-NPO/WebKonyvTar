<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, HasUuids;

    // The table only has created_at
    public const UPDATED_AT = null;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password_hash',
        'role',
        'status',
        'gdpr_accepted',
        'gdpr_accepted_at',
    ];

    protected $hidden = ['password_hash'];

    protected $casts = [
        'password_hash' => 'hashed', // plain text assigned here is hashed automatically
        'gdpr_accepted' => 'boolean',
        'gdpr_accepted_at' => 'datetime',
    ];

    /** Laravel's auth looks for a `password` column by default; ours is password_hash. */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    /** No remember_token column in the schema, so Laravel's remember cookie is disabled. */
    public function getRememberTokenName(): string
    {
        return '';
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->last_name} {$this->first_name}";
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isLibrarian(): bool
    {
        return in_array($this->role, ['librarian', 'admin'], true);
    }

    public function passwordResetTokens(): HasMany
    {
        return $this->hasMany(PasswordResetToken::class);
    }

    public function loginOtpTokens(): HasMany
    {
        return $this->hasMany(LoginOtpToken::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(UserSession::class);
    }
}
