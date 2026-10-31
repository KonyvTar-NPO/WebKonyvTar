<nav class="relative text-gray-300 dark:text-white bg-gray-700 dark:bg-gray-800 w-11/12 z-20 rounded-2xl mt-3 mx-auto">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-3">

        <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <!--<img src="{{asset("logo.png")}}" class="h-7" alt="logo" />-->
            <span class="self-center text-2xl text-heading font-semibold whitespace-nowrap">KönyvTár</span>
        </a>

        <div class="flex relative items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">

            <button type="button" class="flex text-sm rounded-full md:me-0" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                <img class="md:w-8 md:h-8 w-6 h-6 rounded-full" src="{{asset("pfp.jpg")}}" alt="user photo">
            </button>

            <!-- Dropdown menu -->
            <div class="z-50 absolute top-13.5 -right-3 hidden rounded-lg bg-gray-300 text-gray-700 border-gray-700 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-300 dark:border-1 dark:divide-gray-300 border-2 divide-y w-44" id="user-dropdown">

                <!-- TBD comparison -->
                @if(1 == 2)
                    <div class="px-4 py-3 text-sm">
                        <span class="block text-heading font-medium">Lorem ipsum.</span>
                        <span class="block text-body truncate">name@website.com</span>
                    </div>

                    <ul class="p-2 text-sm text-body font-medium" aria-labelledby="user-menu-button">
                        <li>
                            <a href="#" class="inline-flex items-center w-full p-2 hover:bg-gray-400 dark:hover:bg-gray-700 rounded">Profil</a>
                        </li>
                        <li>
                            <a href="#" class="inline-flex items-center w-full p-2 hover:bg-gray-400 dark:hover:bg-gray-700 rounded">Könyveim</a>
                        </li>
                        <li>
                            <!-- TODO: ha van új üzenet akkor (+{üzenetek száma}) a gomb szövege után: Chatek (+4) -->
                            <a href="#" class="inline-flex items-center w-full p-2 hover:bg-gray-400 dark:hover:bg-gray-700 rounded">Értesítések</a>
                        </li>
                        <li>
                            <a href="#" class="inline-flex items-center w-full p-2 hover:bg-gray-400 dark:hover:bg-gray-700 rounded">Beállítások</a>
                        </li>
                        <li>
                            <a href="#" class="inline-flex items-center w-full p-2 text-red-700 hover:bg-gray-400 dark:hover:bg-gray-700 rounded">Kijelentkezés</a>
                        </li>
                        <li>
                            <button id="theme-toggle" class="text-black dark:text-gray-300">Toggle theme</button>
                        </li>
                    </ul>
                @else
                    <ul class="p-2 text-sm text-body font-medium" aria-labelledby="user-menu-button">
                        <li>
                            <a href="/login" onclick="event.preventDefault(); openDialog('login-dialog')"
                               class="inline-flex items-center w-full p-2 text-blue-600 dark:text-blue-400 hover:bg-gray-400 dark:hover:bg-gray-700 rounded">
                                Bejelentkezés
                            </a>
                        </li>
                        <li>
                            <a href="/register"
                               onclick="event.preventDefault(); openDialog('register-dialog')"
                               class="inline-flex items-center w-full p-2 text-blue-600 dark:text-blue-400 hover:bg-gray-400 dark:hover:bg-gray-700 rounded">
                                Csatlakozz!
                            </a>
                        </li>
                        <li>
                            <button id="theme-toggle" class="text-black dark:text-gray-300">Toggle theme</button>
                        </li>
                    </ul>
                @endif

            </div>
            <button data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-8 h-8 justify-center text-sm text-body md:hidden hover:bg-neutral-secondary-soft hover:text-heading" aria-controls="navbar-user" aria-expanded="false">
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/></svg>
            </button>
        </div>

        <!-- TODO: FIX SIZES AND ALIGNMENT & RESPONSIVE -->
        <div class="items-center px-20 md:px-0 justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
            <ul class="font-medium flex flex-col gap-y-3 md:gap-y-0 px-20 md:p-0 mb-0 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">
                <li>
                    <a href="#" class="inline px-6 py-3 text-base md:border-0">Könyvek</a>
                </li>
                <li>
                    <a href="#" class="inline px-6 py-3 text-base md:border-0">Könyvtárak</a>
                </li>
                <li>
                    <a href="/about" class="inline px-6 py-3 text-base md:border-0">Rólunk</a>
                </li>
            </ul>
        </div>
    </div>
</nav>



<!-- REGISZTRACIO -->

<dialog id="register-dialog"
        onclick="if (event.target === this) this.close()"
        class="m-auto w-full max-w-md rounded-lg bg-gray-200 dark:bg-gray-900 p-0 shadow-xl backdrop:bg-black/60">
    <div class="relative p-6">
        <form method="dialog" class="absolute top-3 right-3">
            <button class="text-gray-600 hover:text-black dark:text-gray-400 dark:hover:text-white">✕</button>
        </form>

        <h2 class="mb-4 text-2xl text-gray-900 dark:text-gray-200">Csatlakozz!</h2>

        <form method="POST" action="" class="space-y-4">
            @csrf
            <input type="hidden" name="_form" value="register">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="first_name" class="mb-1 block text-sm font-medium text-gray-900 dark:text-gray-200">Keresztnév</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}"
                           required autocomplete="given-name"
                           class="w-full h-6.5 px-1 rounded border-1 border-gray-400 bg-white text-gray-900 dark:text-gray-100 dark:border-gray-400 dark:bg-gray-900">
                    @error('first_name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="last_name" class="mb-1 block text-sm font-medium text-gray-900 dark:text-gray-200">Vezetéknév</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                           required autocomplete="family-name"
                           class="w-full h-6.5 px-1 border-1 rounded border-gray-400 bg-white text-gray-900 dark:text-gray-100 dark:border-gray-400 dark:bg-gray-900">
                    @error('last_name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-gray-900 dark:text-gray-200">E-mail cím</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       required autocomplete="email"
                       class="w-full h-6.5 px-1 border-1 rounded border-gray-400 bg-white text-gray-900 dark:text-gray-100 dark:border-gray-400 dark:bg-gray-900">
                @error('email') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="mb-1 block text-sm font-medium text-gray-900 dark:text-gray-200">Jelszó</label>
                <input type="password" id="password" name="password"
                       required autocomplete="new-password"
                       class="w-full h-6.5 px-1 border-1 rounded border-gray-400 bg-white text-gray-900 dark:text-gray-100 dark:border-gray-400 dark:bg-gray-900">
                @error('password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-900 dark:text-gray-200">Jelszó megerősítése</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       required autocomplete="new-password"
                       class="w-full h-6.5 px-1 border-1 rounded border-gray-400 bg-white text-gray-900 dark:text-gray-100 dark:border-gray-400 dark:bg-gray-900">
            </div>

            <button type="submit" class="w-full rounded bg-blue-600 py-2 text-white hover:bg-blue-700">
                Regisztráció
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-800 dark:text-gray-300">
            Van már fiókod?
            <a href="/login" onclick="event.preventDefault(); openDialog('login-dialog')" class="text-blue-700 hover:underline dark:text-blue-400">Bejelentkezés</a>
        </p>
    </div>
</dialog>

@if ($errors->any())
    <script>document.getElementById('register-dialog').showModal()</script>
@endif

<!-- BEJELENTKEZES -->

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
        </p>
    </div>
</dialog>

<script>
    (() => {
        const dialog = document.getElementById('login-dialog');
        const tabs = dialog.querySelectorAll('[data-tab]');
        const panels = dialog.querySelectorAll('[data-panel]');

        tabs.forEach(tab => tab.addEventListener('click', () => {
            tabs.forEach(t => t.setAttribute('aria-selected', t === tab));
            panels.forEach(p => p.hidden = p.dataset.panel !== tab.dataset.tab);
        }));
    })();

    function openDialog(id) {
        document.querySelectorAll('dialog[open]').forEach(d => d.close());
        document.getElementById(id).showModal();
    }
</script>

@if ($isLogin || session('otp_email'))
    <script>document.getElementById('login-dialog').showModal()</script>
@endif
