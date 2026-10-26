<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Login Portal Rundown Digital — TVRI Kalimantan Selatan">

    <title>@yield('title', 'Login') — TVRI Kalimantan Selatan</title>

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Iconify --}}
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    </style>
</head>
<body class="min-h-screen text-slate-800 flex flex-col justify-between antialiased selection:bg-blue-600 selection:text-white">

    {{-- NAVBAR ATAS --}}
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-xs sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-2.5 flex items-center justify-between">
            {{-- Logo TVRI Kalsel --}}
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="inline-block transition hover:opacity-90">
                    <img src="{{ asset('images/logo/tvri_kalsel_lgo.png') }}" alt="TVRI Kalimantan Selatan" class="h-9 sm:h-10 w-auto object-contain">
                </a>
            </div>

            {{-- Navigasi Kanan: Tombol Kembali --}}
            <div class="flex items-center">
                <a href="{{ url('/') }}"
                   class="group inline-flex items-center gap-2 pl-1.5 pr-3.5 py-1.5 rounded-full bg-white hover:bg-blue-50/80 border border-slate-200/90 hover:border-blue-300 text-xs font-bold text-slate-700 hover:text-blue-700 transition-all duration-200 shadow-xs">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-xs group-hover:bg-blue-700 transition-colors">
                        <svg class="w-3.5 h-3.5 transform" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span class="tracking-wide">Kembali</span>
                </a>
            </div>
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 flex flex-col justify-center items-center px-4 py-8 sm:py-12 relative z-10 w-full">
        {{ $slot }}
    </main>

    {{-- FOOTER BAWAH --}}
    <footer class="py-4 border-t border-slate-200/60 bg-white/50 backdrop-blur-sm text-center text-xs text-slate-500">
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
