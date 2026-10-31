<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginOtpToken extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['user_id', 'token', 'expires_at'];

    protected $hidden = ['token'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
