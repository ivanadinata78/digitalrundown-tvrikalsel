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

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-image: url('{{ asset("images/backgrounds/mainbg2.png") }}');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #f4f6fa;
        }
        .live-pulse {
            animation: pulseLive 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulseLive {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.65; transform: scale(0.96); }
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="min-h-screen text-slate-800 flex flex-col justify-between overflow-x-hidden">

    {{-- NAVBAR --}}
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-xs sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-2.5 flex items-center justify-between">

            {{-- Logo TVRI Kalsel --}}
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="inline-block transition hover:opacity-90">
                    <img src="{{ asset('images/logo/tvri_kalsel_lgo.png') }}" alt="TVRI Kalimantan Selatan" class="h-9 sm:h-10 w-auto object-contain">
                </a>
            </div>

            {{-- Nav Menu Tengah --}}
            <nav class="flex items-center gap-6 sm:gap-8">
                <a href="{{ url('/') }}" class="relative py-2 flex items-center gap-1.5 text-sm font-bold text-blue-600 transition">
                    <iconify-icon icon="heroicons:home-solid" width="18" class="text-blue-600"></iconify-icon>
                    <span>Beranda</span>
                    <span class="absolute -bottom-2.5 left-0 right-0 h-[2.5px] bg-blue-600 rounded-full"></span>
                </a>
                <a href="https://tvri.go.id/saluran/tvri-kalimantan-selatan" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1.5 text-sm font-bold text-blue-600 hover:text-blue-700 transition">
                    <span>Live TV</span>
                    <span class="bg-[#d90429] text-white text-[10px] font-extrabold px-1.5 py-0.5 rounded-full live-pulse tracking-wider">LIVE</span>
                </a>
            </nav>

            {{-- Login Button Kanan --}}
            <div class="flex items-center">
                @auth
                    <div class="flex items-center gap-2">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-[#1e3a8a] hover:text-blue-600 flex items-center gap-1">
                                <iconify-icon icon="heroicons:squares-2x2" width="16"></iconify-icon>
                                DASHBOARD
                            </a>
                        @else
                            <a href="{{ route('rundown.index') }}" class="text-sm font-bold text-[#1e3a8a] hover:text-blue-600 flex items-center gap-1">
                                <iconify-icon icon="heroicons:squares-2x2" width="16"></iconify-icon>
                                DASHBOARD
                            </a>
                        @endif
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm font-extrabold text-[#1e3a8a] hover:text-blue-600 tracking-wider transition">
                        LOGIN
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-5 flex-1 w-full">
        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <iconify-icon icon="heroicons:exclamation-circle" width="18"></iconify-icon>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="py-4 border-t border-slate-200/60 mt-6 bg-white/50 backdrop-blur-sm text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo/tvri_kalsel_lgo.png') }}" alt="TVRI" class="h-5 w-auto">
                <span>© {{ date('Y') }} TVRI Kalimantan Selatan. All rights reserved.</span>
            </div>
            <span class="text-slate-400">Rundown Digital v1.0</span>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
