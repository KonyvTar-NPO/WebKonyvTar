<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserSession extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'user_sessions';

    protected $fillable = [
        'user_id',
        'token',
        'ip_address',
        'user_agent',
        'remember_me',
        'expires_at',
    ];

    protected $hidden = ['token'];

    protected $casts = [
        'remember_me' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Record a login. Call it AFTER session()->regenerate() so the stored id is the final one. */
    public static function startFor(Request $request, User $user, bool $remember = false): self
    {
        return static::create([
            'user_id' => $user->id,
            'token' => hash('sha256', $request->session()->getId()),
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
            'remember_me' => $remember,
            'expires_at' => now()->addMinutes($remember ? 60 * 24 * 30 : (int) config('session.lifetime')),
        ]);
    }

    /** Remove the row for the current browser session. Call it BEFORE invalidating the session. */
    public static function endCurrent(Request $request): void
    {
        static::where('token', hash('sha256', $request->session()->getId()))->delete();
    }
}
