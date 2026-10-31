@extends('layouts.app')

@section('title', 'Új jelszó')

@section('content')
    <div class="mx-auto w-full max-w-md rounded-lg bg-gray-200 dark:bg-gray-900 p-6 shadow-xl">
        <h1 class="mb-4 text-2xl text-gray-900 dark:text-gray-200">Új jelszó megadása</h1>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ old('email', $email) }}">

            @error('email')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror

            <x-form.input name="password" type="password" label="Új jelszó" required autocomplete="new-password" />
            <x-form.input name="password_confirmation" type="password" label="Jelszó megerősítése" required autocomplete="new-password" />

            <button type="submit" class="w-full rounded bg-blue-600 py-2 text-white hover:bg-blue-700">
                Jelszó mentése
            </button>
        </form>
    </div>
@endsection
