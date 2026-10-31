<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->update($request->validated());

        return back()->with('status', 'Profil frissítve.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update(['password_hash' => $data['password']]); // hashed by the model cast

        return back()->with('status', 'Jelszó módosítva.');
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        UserSession::endCurrent($request);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete(); // tokens and session logs go with it (cascade)

        return redirect('/')->with('status', 'A fiókod törölve lett :(');
    }
}
