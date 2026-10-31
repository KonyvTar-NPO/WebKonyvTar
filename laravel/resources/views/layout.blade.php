<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title')@yield('title') | @endif{{ config('app.name', 'KönyvTár') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    {{-- Must stay inline in <head> so the theme is applied before first paint --}}
    <script>
        (() => {
            const stored = localStorage.getItem('theme');
            const dark = stored ? stored === 'dark'
                : window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="theme-transition min-h-screen flex flex-col bg-gray-300 dark:bg-gray-950">
<header>
    @include('partials.navbar')
</header>

<main class="flex-1 w-full pt-4 px-5 md:px-20">
    @if (session('status')) <p>{{ session('status') }}</p> @endif
    @yield('content')
</main>

<footer class="text-center my-2.5 text-sm text-gray-950 dark:text-gray-300">
    &copy; KönyvTár {{ date('Y') }} <br> Kolozsvári Barnabás, Szlonkai Benedek
</footer>

@guest
    @include('partials.auth.register-dialog')
    @include('partials.auth.login-dialog')
@endguest

@stack('scripts')
</body>
</html>
