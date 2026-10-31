<nav class="relative text-gray-300 dark:text-white bg-gray-700 dark:bg-gray-800 w-11/12 z-20 rounded-2xl mt-3 mx-auto">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-3">

        <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="self-center text-2xl text-heading font-semibold whitespace-nowrap">KönyvTár</span>
        </a>

        <div class="flex relative items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <button type="button" class="flex text-sm rounded-full md:me-0" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                <img class="md:w-8 md:h-8 w-6 h-6 rounded-full" src="{{asset("pfp.jpg")}}" alt="user photo">
            </button>

            @include('partials.navbar.user-menu')

            <button data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-8 h-8 justify-center text-sm text-body md:hidden hover:bg-neutral-secondary-soft hover:text-heading" aria-controls="navbar-user" aria-expanded="false">
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/></svg>
            </button>
        </div>

        <!-- TODO: FIX SIZES AND ALIGNMENT & RESPONSIVE, array and foreach for links -->
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
