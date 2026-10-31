<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OtpLoginController extends Controller
{
    private const CODE_TTL_MINUTES = 10;

    /** Step 1: email a one-time code. The response is identical whether or not the account exists. */
    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $email = Str::lower(trim($data['email']));

        $key = 'otp-send:'.$email;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            throw ValidationException::withMessages([
                'email' => 'Túl sok kódkérés. Próbáld újra később.',
            ]);
        }
        RateLimiter::hit($key, 600);

        $user = User::where('email', $email)->where('status', 'active')->first();

        if ($user) {
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $user->loginOtpTokens()->delete();
            $user->loginOtpTokens()->create([
                'token' => Hash::make($code),
                'expires_at' => now()->addMinutes(self::CODE_TTL_MINUTES),
            ]);

            Mail::raw(
                "A KönyvTár belépési kódod: {$code}\n\nA kód ".self::CODE_TTL_MINUTES." percig érvényes. Ha nem te kérted, hagyd figyelmen kívül ezt a levelet.",
                fn ($message) => $message->to($user->email)->subject('KönyvTár belépési kód')
            );
        }

        // otp_email makes the login dialog show the "enter code" step
        return back()
            ->with('otp_email', $email)
            ->withInput(['_form' => 'login']);
    }

    /** Step 2: check the code. */
    public function verify(Request $request): RedirectResponse
    {
        $email = Str::lower(trim((string) $request->input('email')));

        // Keep the "enter code" step visible if anything below fails;
        // it is forgotten again on success.
        $request->session()->flash('otp_email', $email);

        $data = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
        ]);

        // Limited per email (not per IP) so a 6 digit code can't be brute forced from many IPs
        $key = 'otp-verify:'.$email;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'code' => 'Túl sok hibás kísérlet. Kérj új kódot később.',
            ]);
        }

        $user = User::where('email', $email)->where('status', 'active')->first();

        $token = $user?->loginOtpTokens()
            ->where('expires_at', '>', now())
            ->latest('id')
            ->first();

        if (! $user || ! $token || ! Hash::check($data['code'], $token->token)) {
            RateLimiter::hit($key, 600);

            throw ValidationException::withMessages([
                'code' => 'Hibás vagy lejárt kód.',
            ]);
        }

        $user->loginOtpTokens()->delete(); // single use
        RateLimiter::clear($key);
        $request->session()->forget('otp_email');

        Auth::login($user);
        $request->session()->regenerate();
        UserSession::startFor($request, $user);

        return redirect()->intended('/');
    }
}
