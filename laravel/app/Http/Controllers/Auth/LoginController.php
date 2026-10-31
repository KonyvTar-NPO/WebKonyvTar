<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = $credentials['email'].'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Túl sok sikertelen próbálkozás. Próbáld újra {$seconds} másodperc múlva.",
            ]);
        }

        $remember = $request->boolean('remember');

        // Only active accounts can log in
        if (! Auth::attempt([...$credentials, 'status' => 'active'], $remember)) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => 'Hibás e-mail cím vagy jelszó.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();
        UserSession::startFor($request, Auth::user(), $remember);

        return redirect()->intended('/');
    }

    public function destroy(Request $request): RedirectResponse
    {
        UserSession::endCurrent($request);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
