<div id="user-dropdown" class="z-50 absolute top-13.5 -right-3 hidden rounded-lg bg-gray-300 text-gray-700 border-gray-700 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-300 dark:border-1 dark:divide-gray-300 border-2 divide-y w-44">
    @auth
        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="user-menu-button">
            <li>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center w-full p-2 hover:bg-gray-400 dark:hover:bg-gray-700 rounded">Profil</a>
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
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center w-full p-2 text-red-700 hover:bg-gray-400 dark:hover:bg-gray-700 rounded">Kijelentkezés</button>
                </form>
            </li>
        </ul>
    @else
        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="user-menu-button">
            <li><a href="/login" onclick="event.preventDefault(); openDialog('login-dialog')" class="inline-flex items-center w-full p-2 text-blue-600 dark:text-blue-400 hover:bg-gray-400 dark:hover:bg-gray-700 rounded">Bejelentkezés</a></li>
            <li><a href="/register" onclick="event.preventDefault(); openDialog('register-dialog')" class="inline-flex items-center w-full p-2 text-blue-600 dark:text-blue-400 hover:bg-gray-400 dark:hover:bg-gray-700 rounded">Csatlakozz!</a></li>
        </ul>
    @endauth

        <ul class="p-2 text-sm font-medium border-t border-gray-400 dark:border-gray-600">
            <li>
                <button data-theme-toggle class="text-black dark:text-gray-300">Toggle theme</button>
            </li>
        </ul>
</div>
