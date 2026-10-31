<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset("style.css") }}">
    <link rel="icon" type="image/x-icon" href="{{ asset("logo.png") }}">
    <title>{{ config('app.name', 'KönyvTár') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    </script>
    <!-- scanner library -->
    <script src="{{ asset("html5-qrcode.min.js") }}"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="select-none theme-transition dark:bg-gray-950 bg-gray-300">
    <header>
        @include("navbar")
    </header>

    <main class="pt-4 px-5 md:px-20">
        @yield("content")
    </main>

    <footer class="text-center my-2.5 dark:text-gray-300 text-gray-950 text-sm ">
        &copy; KönyvTár 2026 <br> Kolozsvári Barnabás, Szlonkai Benedek
    </footer>

    <!-- TODO: place it somewhere useful with button -->
    <script>
        document.getElementById('theme-toggle').addEventListener('click', () => {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.theme = html.classList.contains('dark') ? 'dark' : 'light';
        });
    </script>

</body>
</html>
