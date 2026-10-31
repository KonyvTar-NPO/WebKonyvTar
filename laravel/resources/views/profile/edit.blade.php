@extends('layout')
@section('title', 'Profil')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">

        <section class="rounded-lg bg-gray-200 dark:bg-gray-900 p-6 shadow">
            <h2 class="mb-4 text-xl text-gray-900 dark:text-gray-200">Adataim</h2>
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf @method('PATCH')
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form.input name="first_name" label="Keresztnév" :value="$user->first_name" required />
                    <x-form.input name="last_name" label="Vezetéknév" :value="$user->last_name" required />
                </div>
                <x-form.input name="email" type="email" label="E-mail cím" :value="$user->email" required />
                <x-form.input name="phone_number" type="tel" label="Telefonszám" :value="$user->phone_number" />
                <button class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Mentés</button>
            </form>
        </section>

        <section class="rounded-lg bg-gray-200 dark:bg-gray-900 p-6 shadow">
            <h2 class="mb-4 text-xl text-gray-900 dark:text-gray-200">Jelszó módosítása</h2>
            <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                @csrf @method('PUT')
                <x-form.input name="current_password" type="password" label="Jelenlegi jelszó" bag="updatePassword" autocomplete="current-password" required />
                <x-form.input name="password" type="password" label="Új jelszó" bag="updatePassword" autocomplete="new-password" required />
                <x-form.input name="password_confirmation" type="password" label="Új jelszó megerősítése" bag="updatePassword" autocomplete="new-password" required />
                <button class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Jelszó mentése</button>
            </form>
        </section>

        <section class="rounded-lg bg-gray-200 dark:bg-gray-900 p-6 shadow">
            <h2 class="mb-2 text-xl text-red-700 dark:text-red-400">Fiók törlése</h2>
            <p class="mb-4 text-sm text-gray-800 dark:text-gray-300">A törlés végleges. Add meg a jelszavad a megerősítéshez.</p>
            <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4"
                  onsubmit="return confirm('Biztosan törlöd a fiókodat?')">
                @csrf @method('DELETE')
                <x-form.input name="password" type="password" label="Jelszó" bag="userDeletion" id="delete_password" required />
                <button class="rounded bg-red-700 px-4 py-2 text-white hover:bg-red-800">Fiók törlése</button>
            </form>
        </section>
    </div>
@endsection
