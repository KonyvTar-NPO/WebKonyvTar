<?php

namespace App\Http\Controllers;

use App\Mail\LoginCodeMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpLoginController extends Controller
{
    private const CODE_TTL_MINUTES = 10;
    private const MAX_ATTEMPTS = 5;

    /**
     * Step 1: generate a one-time code and email it.
     */
    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = Str::lower($data['email']);

        if (User::where('email', $email)->exists()) {
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Only the hash is stored, and a new code resets the attempt counter.
            Cache::put($this->codeKey($email), Hash::make($code), now()->addMinutes(self::CODE_TTL_MINUTES));
            Cache::forget($this->attemptsKey($email));

            Mail::to($email)->send(new LoginCodeMail($code));
        }

        // Same response whether or not the user exists, so emails can't be enumerated.
        return back()
            ->with('otp_email', $email)
            ->withInput(['_form' => 'login']);
    }

    /**
     * Step 2: check the code and log the user in.
     */
    public function verify(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'code'  => ['required', 'digits:6'],
        ]);

        $email = Str::lower($data['email']);

        $hash = Cache::get($this->codeKey($email));

        if (! $hash) {
            return $this->fail($email, 'Hibás vagy lejárt kód.');
        }

        // Per-email attempt limit: a 6-digit code is brute-forceable without it.
        Cache::add($this->attemptsKey($email), 0, now()->addMinutes(self::CODE_TTL_MINUTES));
        $attempts = Cache::increment($this->attemptsKey($email));

        if ($attempts > self::MAX_ATTEMPTS) {
            Cache::forget($this->codeKey($email));
            Cache::forget($this->attemptsKey($email));

            return $this->fail($email, 'Túl sok próbálkozás. Kérj új kódot.');
        }

        if (! Hash::check($data['code'], $hash)) {
            return $this->fail($email, 'Hibás vagy lejárt kód.');
        }

        // Single use.
        Cache::forget($this->codeKey($email));
        Cache::forget($this->attemptsKey($email));

        $user = User::where('email', $email)->first();

        if (! $user) {
            return $this->fail($email, 'Hibás vagy lejárt kód.');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    private function fail(string $email, string $message): RedirectResponse
    {
        return back()
            ->withErrors(['code' => $message])
            ->with('otp_email', $email)
            ->withInput(['_form' => 'login']);
    }

    private function codeKey(string $email): string
    {
        return 'login_otp:' . $email;
    }

    private function attemptsKey(string $email): string
    {
        return 'login_otp_attempts:' . $email;
    }
}
