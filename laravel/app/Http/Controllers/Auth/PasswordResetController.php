<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    private const LINK_TTL_MINUTES = 60;

    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $email = Str::lower(trim($data['email']));

        $user = User::where('email', $email)->where('status', 'active')->first();

        if ($user) {
            $plainToken = Str::random(64);

            // Only the newest link works
            $user->passwordResetTokens()->where('used', false)->delete();
            $user->passwordResetTokens()->create([
                'token' => hash('sha256', $plainToken),
                'expires_at' => now()->addMinutes(self::LINK_TTL_MINUTES),
                'used' => false,
            ]);

            $url = route('password.reset', ['token' => $plainToken, 'email' => $email]);

            Mail::raw(
                "Jelszó-visszaállítást kértél a KönyvTárban.\n\n{$url}\n\nA link ".self::LINK_TTL_MINUTES." percig érvényes. Ha nem te kérted, hagyd figyelmen kívül ezt a levelet.",
                fn ($message) => $message->to($user->email)->subject('KönyvTár jelszó-visszaállítás')
            );
        }

        // Same answer either way, so the form can't be used to find out who has an account
        return back()->with('status', 'Ha ez az e-mail cím regisztrálva van, elküldtük a jelszó-visszaállító linket.');
    }

    public function edit(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::where('email', $data['email'])->first();

        $record = $user?->passwordResetTokens()
            ->where('token', hash('sha256', $data['token']))
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (! $record) {
            throw ValidationException::withMessages([
                'email' => 'A link érvénytelen vagy lejárt. Kérj újat.',
            ]);
        }

        DB::transaction(function () use ($user, $record, $data) {
            $user->update(['password_hash' => $data['password']]); // hashed by the model cast
            $record->update(['used' => true]);
        });

        return redirect('/')->with('status', 'A jelszavad megváltozott, most már bejelentkezhetsz.');
    }
}
