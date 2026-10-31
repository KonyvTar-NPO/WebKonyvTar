@php
    $isLogin = old('_form') === 'login';
    $otpStep = session('otp_email');
@endphp

<dialog id="login-dialog"
        onclick="if (event.target === this) this.close()"
        class="m-auto w-full max-w-md rounded-lg bg-gray-200 dark:bg-gray-900 p-0 shadow-xl backdrop:bg-black/60">
    <div class="relative p-6">
        <form method="dialog" class="absolute top-3 right-3">
            <button class="text-gray-600 hover:text-black dark:text-gray-400 dark:hover:text-white">✕</button>
        </form>

        <h2 class="mb-4 text-2xl text-gray-900 dark:text-gray-200">Bejelentkezés</h2>

        {{-- tabs --}}
        <div class="mb-5 flex border-b border-gray-400 dark:border-gray-600" role="tablist">
            <button type="button" role="tab" data-tab="password" aria-selected="{{ $otpStep ? 'false' : 'true' }}"
                    class="-mb-px flex-1 border-b-2 border-transparent py-2 text-sm text-gray-700 dark:text-gray-400 aria-selected:border-blue-600 aria-selected:text-blue-700 dark:aria-selected:text-blue-400">
                Jelszóval
            </button>
            <button type="button" role="tab" data-tab="otp" aria-selected="{{ $otpStep ? 'true' : 'false' }}"
                    class="-mb-px flex-1 border-b-2 border-transparent py-2 text-sm text-gray-700 dark:text-gray-400 aria-selected:border-blue-600 aria-selected:text-blue-700 dark:aria-selected:text-blue-400">
                Kóddal
            </button>
        </div>

        {{-- PASSWORD LOGIN --}}
        <form data-panel="password" method="POST" action="{{ route('login') }}" class="space-y-4" @if($otpStep) hidden @endif>
            @csrf
            <input type="hidden" name="_form" value="login">

            <div>
                <label for="login_email" class="mb-1 block text-sm font-medium text-gray-900 dark:text-gray-200">E-mail cím</label>
                <input type="email" id="login_email" name="email" value="{{ $isLogin ? old('email') : '' }}"
                       required autocomplete="email"
                       class="w-full h-6.5 px-1 border-1 rounded border-gray-400 bg-white text-gray-900 dark:text-gray-100 dark:border-gray-400 dark:bg-gray-900">
                @if ($isLogin) @error('email') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror @endif
            </div>

            <div>
                <label for="login_password" class="mb-1 block text-sm font-medium text-gray-900 dark:text-gray-200">Jelszó</label>
                <input type="password" id="login_password" name="password"
                       required autocomplete="current-password"
                       class="w-full h-6.5 px-1 border-1 rounded border-gray-400 bg-white text-gray-900 dark:text-gray-100 dark:border-gray-400 dark:bg-gray-900">
                @if ($isLogin) @error('password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror @endif
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-300">
                <input type="checkbox" name="remember" class="rounded border-gray-400"> Emlékezz rám
            </label>

            <button type="submit" class="w-full rounded bg-blue-600 py-2 text-white hover:bg-blue-700">Bejelentkezés</button>
        </form>

        {{-- OTP LOGIN, step 1: ask for email --}}
        @unless ($otpStep)
            <form data-panel="otp" method="POST" action="{{ url('/login/otp/send') }}" class="space-y-4" hidden>
                @csrf
                <input type="hidden" name="_form" value="login">

                <div>
                    <label for="otp_email" class="mb-1 block text-sm font-medium text-gray-900 dark:text-gray-200">E-mail cím</label>
                    <input type="email" id="otp_email" name="email" required autocomplete="email"
                           class="w-full h-6.5 px-1 border-1 rounded border-gray-400 bg-white text-gray-900 dark:text-gray-100 dark:border-gray-400 dark:bg-gray-900">
                </div>

                <button type="submit" class="w-full rounded bg-blue-600 py-2 text-white hover:bg-blue-700">Kód küldése</button>
            </form>
        @else
            {{-- OTP LOGIN, step 2: enter the code --}}
            <form data-panel="otp" method="POST" action="{{ url('/login/otp/verify') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="_form" value="login">
                <input type="hidden" name="email" value="{{ $otpStep }}">

                <p class="text-sm text-gray-800 dark:text-gray-300">
                    Elküldtük a kódot ide: <strong>{{ $otpStep }}</strong>
                </p>

                <div>
                    <label for="otp_code" class="mb-1 block text-sm font-medium text-gray-900 dark:text-gray-200">Egyszeri kód</label>
                    <input type="text" id="otp_code" name="code" required
                           inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code"
                           class="w-full h-6.5 px-1 border-1 rounded border-gray-400 bg-white text-center tracking-[0.4em] text-gray-900 dark:text-gray-100 dark:border-gray-400 dark:bg-gray-900">
                    @if ($isLogin) @error('code') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror @endif
                </div>

                <button type="submit" class="w-full rounded bg-blue-600 py-2 text-white hover:bg-blue-700">Ellenőrzés</button>
            </form>
        @endunless

        <p class="mt-4 text-center text-sm text-gray-800 dark:text-gray-300">
            Még nincs fiókod?
            <a href="" class="text-blue-700 hover:underline dark:text-blue-400"
               onclick="event.preventDefault(); openDialog('register-dialog')">
                Regisztráció
            </a>
            {{--<a href="{{ route('password.request') }}">Elfelejtett jelszó?</a>--}}
        </p>
    </div>
</dialog>

@if ($isLogin || session('otp_email'))
    <script>document.getElementById('login-dialog').showModal()</script>
@endif
