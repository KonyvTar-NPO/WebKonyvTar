<dialog id="register-dialog" onclick="if (event.target === this) this.close()"
        class="m-auto w-full max-w-md rounded-lg bg-gray-200 dark:bg-gray-900 p-0 shadow-xl backdrop:bg-black/60">
    <div class="relative p-6">
        <form method="dialog" class="absolute top-3 right-3">
            <button class="text-gray-600 hover:text-black dark:text-gray-400 dark:hover:text-white">✕</button>
        </form>

        <h2 class="mb-4 text-2xl text-gray-900 dark:text-gray-200">Csatlakozz!</h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="_form" value="register">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.input name="first_name" label="Keresztnév" form="register" required autocomplete="given-name" />
                <x-form.input name="last_name"  label="Vezetéknév" form="register" required autocomplete="family-name" />
            </div>
            <x-form.input name="email" type="email" label="E-mail cím" form="register" required autocomplete="email" />
            <x-form.input name="password" type="password" label="Jelszó" form="register" required autocomplete="new-password" />
            <x-form.input name="password_confirmation" type="password" label="Jelszó megerősítése" required autocomplete="new-password" />
            <x-form.input name="phone_number" type="tel" label="Telefonszám (nem kötelező)" form="register" autocomplete="tel" />

            <label class="flex items-start gap-2 text-sm text-gray-800 dark:text-gray-300">
                <input type="checkbox" name="gdpr_accepted" value="1" required class="mt-1 rounded border-gray-400" @checked(old('gdpr_accepted'))>
                Elfogadom az adatkezelési tájékoztatót.
            </label>
            @if (old('_form') === 'register') @error('gdpr_accepted') <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror @endif

            <button type="submit" class="w-full rounded bg-blue-600 py-2 text-white hover:bg-blue-700">Regisztráció</button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-800 dark:text-gray-300">
            Van már fiókod?
            <a href="/login" onclick="event.preventDefault(); openDialog('login-dialog')" class="text-blue-700 hover:underline dark:text-blue-400">Bejelentkezés</a>
        </p>
    </div>
</dialog>

@if (old('_form') === 'register' && $errors->any())
    <script>document.getElementById('register-dialog').showModal()</script>
@endif
