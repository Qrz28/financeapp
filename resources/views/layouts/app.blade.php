<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#f4f6fc]">
        <div class="min-h-screen flex justify-center">
            <div class="w-full @hasSection('full-width') @else max-w-md @endif bg-white min-h-screen relative shadow-2xl">
                @hasSection('hide-navigation')
                    <!-- Navigation hidden for mobile app views -->
                @else
                    @include('layouts.navigation')
                @endif

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="pb-20">
                    @yield('content')
                </main>
            </div>
        </div>

        @if(request()->routeIs('dashboard') || request()->routeIs('transactions.index'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let currentTimestamp = null;
                let currentCount = null;

                function checkLatestTransaction() {
                    fetch('{{ route('transactions.latest') }}')
                        .then(response => response.json())
                        .then(data => {
                            if (currentTimestamp === null && currentCount === null) {
                                // Initialize on first load
                                currentTimestamp = data.latest_timestamp;
                                currentCount = data.count;
                            } else {
                                // Check for changes
                                if (currentTimestamp !== data.latest_timestamp || currentCount !== data.count) {
                                    window.location.reload();
                                }
                            }
                        })
                        .catch(err => console.error('Polling error:', err));
                }

                // Check every 3 seconds (3000ms), but ONLY on Desktop to avoid lag on HP
                if (window.innerWidth > 1024) {
                    setInterval(checkLatestTransaction, 3000);
                }
            });
        </script>
        @endif
    </body>
</html>
