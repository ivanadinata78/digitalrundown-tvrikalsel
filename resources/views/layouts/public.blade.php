<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rundown Digital TVRI Kalimantan Selatan - Jadwal acara siaran harian">
    <title>@yield('title', 'Rundown Digital') — TVRI Kalimantan Selatan</title>

    {{-- Vite CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Iconify --}}
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-image: url('/images/backgrounds/mainbg2.png');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }
        .live-pulse { animation: pulse 1.5s infinite; }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .tab-active {
            background: #003d99;
            color: white;
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="min-h-screen">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-between">

            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo/tvrikalsel_icon.png') }}" alt="TVRI Kalsel" class="h-10 w-auto">
                <div class="hidden sm:block">
                    <p class="text-xs text-gray-400 leading-tight">RUNDOWN DIGITAL</p>
                    <p class="text-xs font-bold text-blue-900 leading-tight">KALIMANTAN SELATAN</p>
                </div>
            </div>

            {{-- Nav Menu --}}
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}" class="flex items-center gap-1.5 text-sm font-medium text-blue-900 hover:text-blue-600 transition">
                    <iconify-icon icon="heroicons:home" width="16"></iconify-icon>
                    Beranda
                </a>
                <a href="#" class="flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-blue-600 transition">
                    <iconify-icon icon="heroicons:tv" width="16"></iconify-icon>
                    Live TV
                    <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded live-pulse">LIVE</span>
                </a>
            </div>

            {{-- Login Button --}}
            @auth
                <div class="flex items-center gap-2">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-blue-700 hover:underline flex items-center gap-1">
                            <iconify-icon icon="heroicons:squares-2x2" width="16"></iconify-icon>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('rundown.index') }}" class="text-sm font-medium text-blue-700 hover:underline flex items-center gap-1">
                            <iconify-icon icon="heroicons:squares-2x2" width="16"></iconify-icon>
                            Dashboard
                        </a>
                    @endif
                </div>
            @else
                <a href="{{ route('login') }}"
                   class="flex items-center gap-1.5 bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                    <iconify-icon icon="heroicons:lock-closed" width="15"></iconify-icon>
                    LOGIN
                </a>
            @endauth
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="max-w-7xl mx-auto px-4 py-5">
        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <iconify-icon icon="heroicons:exclamation-circle" width="18"></iconify-icon>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-blue-900 text-white mt-8 py-4">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo/logowhite.png') }}" alt="TVRI" class="h-6 w-auto">
                <span class="text-xs text-blue-200">© {{ date('Y') }} TVRI Kalimantan Selatan. All rights reserved.</span>
            </div>
            <span class="text-xs text-blue-300">Rundown Digital v1.0</span>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
