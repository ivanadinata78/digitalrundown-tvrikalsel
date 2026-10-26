<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'TVRI Kalsel')) — Rundown Digital TVRI Kalsel</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="min-h-screen text-slate-800 bg-slate-50 flex flex-col justify-between antialiased selection:bg-blue-600 selection:text-white">
    <div class="min-h-screen flex flex-col justify-between">
        <div>
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-slate-200/80 shadow-xs">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="py-6 sm:py-8">
                @if(session('status'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-5">
                        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2.5 shadow-xs">
                            <iconify-icon icon="heroicons:check-circle-solid" width="20" class="text-emerald-600 shrink-0"></iconify-icon>
                            <span>{{ session('status') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-5">
                        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold flex items-center gap-2.5 shadow-xs">
                            <iconify-icon icon="heroicons:exclamation-triangle-solid" width="20" class="text-red-600 shrink-0"></iconify-icon>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>

        <!-- Footer -->
        <footer class="py-4 border-t border-slate-200/70 bg-white text-center text-xs text-slate-500">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo/tvri_kalsel_lgo.png') }}" alt="TVRI" class="h-5 w-auto">
                    <span>© {{ date('Y') }} TVRI Kalimantan Selatan. All rights reserved.</span>
                </div>
                <span class="text-slate-400 font-medium">Internal Digital Rundown System</span>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
