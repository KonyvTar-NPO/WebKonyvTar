@if (session('status'))
    <div id="flash-message" role="status"
         class="mx-auto mt-3 w-11/12 max-w-screen-xl rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800 dark:bg-green-900 dark:text-green-100">
        {{ session('status') }}
    </div>
    <script>
        setTimeout(() => document.getElementById('flash-message')?.remove(), 6000);
    </script>
@endif
