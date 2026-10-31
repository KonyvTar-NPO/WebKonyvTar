@extends('layouts.app')

@section('title', 'Elfelejtett jelszó')

@section('content')
    <div class="mx-auto w-full max-w-md rounded-lg bg-gray-200 dark:bg-gray-900 p-6 shadow-xl">
        <h1 class="mb-4 text-2xl text-gray-900 dark:text-gray-200">Elfelejtett jelszó</h1>

        @if (session('status'))
            <p class="mb-4 text-sm text-green-700 dark:text-green-400">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <x-form.input name="email" type="email" label="E-mail cím" required autocomplete="email" />

            <button type="submit" class="w-full rounded bg-blue-600 py-2 text-white hover:bg-blue-700">
                Link küldése
            </button>
        </form>
    </div>
@endsection
