@extends('layouts.public')

@section('title', 'Rundown Acara Hari Ini')

@section('content')

{{-- GRID UTAMA: Konten kiri + Sidebar kanan --}}
<div class="flex flex-col lg:flex-row gap-5">

    {{-- ===== KOLOM KIRI ===== --}}
    <div class="flex-1 min-w-0 space-y-5">

        {{-- HERO: Program Sedang Tayang --}}
        <div class="relative rounded-2xl overflow-hidden shadow-xl" style="height: 220px;">

            {{-- Background utama: bannertop.png --}}
            <img src="{{ asset('images/backgrounds/bannertop.png') }}"
                 alt="Banner"
                 class="absolute inset-0 w-full h-full object-cover">

            {{-- Overlay gradient biru gelap di kiri --}}
            <div class="absolute inset-0" style="background: linear-gradient(90deg, rgba(0,20,80,0.92) 0%, rgba(0,40,120,0.80) 45%, rgba(0,50,140,0.30) 75%, transparent 100%);"></div>

            <div class="relative z-10 flex h-full items-stretch">

                {{-- INFO KIRI --}}
                <div class="flex-1 p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-md flex items-center gap-1 live-pulse">
                                <iconify-icon icon="heroicons:signal" width="12"></iconify-icon>
                                SEDANG TAYANG
                            </span>
                            <span class="bg-white/20 backdrop-blur text-white text-xs font-semibold px-2.5 py-1 rounded-md">KALSEL</span>
                        </div>
                        <h1 id="hero-title" class="text-white text-3xl sm:text-4xl font-extrabold leading-tight drop-shadow-lg">
                            HABAR BANUA
                        </h1>
                        <p class="text-blue-200 text-sm mt-1 font-medium">Kalimantan Selatan</p>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 text-white text-sm mb-1">
                            <iconify-icon icon="heroicons:clock" width="15"></iconify-icon>
                            <span id="hero-time" class="font-bold tracking-wide">09:01 - 10:00</span>
                            <span class="w-2 h-2 bg-red-400 rounded-full live-pulse"></span>
                            <span class="text-red-300 font-extrabold text-xs tracking-widest">LIVE</span>
                        </div>
                        <p class="text-blue-200 text-xs max-w-xs leading-relaxed">Menampilkan berita dan informasi terkini seputar Kalimantan Selatan</p>
                    </div>
                </div>

                {{-- THUMBNAIL KANAN — tampil besar seperti konsep --}}
                <div class="relative flex-shrink-0" style="width: 280px;">
                    {{-- Gradient kiri agar menyatu dengan konten --}}
                    <div class="absolute inset-y-0 left-0 w-16 z-10" style="background: linear-gradient(to right, rgba(0,40,120,0.6), transparent);"></div>
                    <img id="hero-thumbnail"
                         src="{{ asset('images/thumbnails/habarbanua.jpg') }}"
                         alt="Habar Banua"
                         class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        {{-- TABEL RUNDOWN --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

            {{-- Header Tabel --}}
            <div class="px-5 pt-5 pb-3 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <iconify-icon icon="heroicons:calendar-days" class="text-blue-700" width="20"></iconify-icon>
                            Rundown Acara Hari Ini
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                    </div>

                    {{-- Filter Tabs --}}
                    <div class="flex flex-wrap gap-1.5" id="filterTabs">
                        @php
                            $tabs = ['Semua', 'Nasional', 'Kalsel', 'Talkshow', 'Relay', 'Dokumenter'];
                        @endphp
                        @foreach($tabs as $tab)
                            <button onclick="filterTab('{{ $tab }}')"
                                    data-tab="{{ $tab }}"
                                    class="tab-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition-all border border-gray-200 text-gray-600 hover:border-blue-300 hover:text-blue-700 {{ $loop->first ? 'tab-active border-blue-900' : '' }}">
                                {{ $tab }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 w-10">No</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 w-24">Waktu</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Mata Acara</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 w-24">Channel</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 w-20">Durasi</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 w-28">Status</th>
                        </tr>
                    </thead>
                    <tbody id="rundownTable">
                        {{-- Data akan diisi dari Google Sheets API --}}
                        {{-- Sementara tampilkan data dummy --}}
                        @php
                            $dummyData = [
                                ['no' => 1,  'waktu' => '07:00', 'acara' => 'KLIK INDONESIA PAGI',     'thumbnail' => null,                    'channel' => 'Nasional', 'durasi' => '1:00:00', 'status' => 'sudah_tayang'],
                                ['no' => 2,  'waktu' => '08:00', 'acara' => 'JENDELA NEGERI',           'thumbnail' => null,                    'channel' => 'Nasional', 'durasi' => '1:00:00', 'status' => 'sudah_tayang'],
                                ['no' => 3,  'waktu' => '09:00', 'acara' => 'STING',                   'thumbnail' => null,                    'channel' => 'Kalsel',   'durasi' => '0:00:30', 'status' => 'sudah_tayang'],
                                ['no' => 4,  'waktu' => '09:01', 'acara' => 'HABAR BANUA',             'thumbnail' => 'habarbanua.jpg',        'channel' => 'Kalsel',   'durasi' => '0:59:00', 'status' => 'on_air'],
                                ['no' => 5,  'waktu' => '10:00', 'acara' => 'INI BORNEO',              'thumbnail' => 'ini_borneo_thumb.jpg',  'channel' => 'Kalsel',   'durasi' => '1:59:00', 'status' => 'menunggu'],
                                ['no' => 6,  'waktu' => '12:00', 'acara' => 'SEBLAK SHOW',             'thumbnail' => null,                    'channel' => 'Nasional', 'durasi' => '1:00:00', 'status' => 'menunggu'],
                                ['no' => 7,  'waktu' => '13:00', 'acara' => 'KLIK INDONESIA SIANG',    'thumbnail' => null,                    'channel' => 'Nasional', 'durasi' => '1:00:00', 'status' => 'menunggu'],
                                ['no' => 8,  'waktu' => '14:00', 'acara' => 'TAPAL BATAS',             'thumbnail' => null,                    'channel' => 'Nasional', 'durasi' => '0:30:00', 'status' => 'menunggu'],
                                ['no' => 9,  'waktu' => '15:00', 'acara' => 'PERSPEKTIF',              'thumbnail' => 'perspektif_thumb.jpg',  'channel' => 'Kalsel',   'durasi' => '0:59:00', 'status' => 'menunggu'],
                                ['no' => 10, 'waktu' => '16:00', 'acara' => 'KALSEL HARI INI',         'thumbnail' => 'kalseltoday_thumb.jpg', 'channel' => 'Kalsel',   'durasi' => '0:59:00', 'status' => 'menunggu'],
                                ['no' => 11, 'waktu' => '17:00', 'acara' => 'KAJIAN TAUHID',           'thumbnail' => 'kajian_thumb.jpg',      'channel' => 'Kalsel',   'durasi' => '0:59:00', 'status' => 'menunggu'],
                                ['no' => 12, 'waktu' => '18:00', 'acara' => 'ADZAN MAGHRIB',           'thumbnail' => 'adzan_thumb.jpg',       'channel' => 'Kalsel',   'durasi' => '0:05:00', 'status' => 'menunggu'],
                                ['no' => 13, 'waktu' => '18:05', 'acara' => 'MAWARUNG',                'thumbnail' => 'mawarung_thumb.jpg',    'channel' => 'Kalsel',   'durasi' => '0:55:00', 'status' => 'menunggu'],
                            ];
                        @endphp

                        @foreach($dummyData as $row)
                            @php
                                $isOnAir = $row['status'] === 'on_air';
                                $isSudah = $row['status'] === 'sudah_tayang';
                            @endphp
                            <tr class="border-b border-gray-50 transition-colors hover:bg-blue-50/30 {{ $isOnAir ? 'bg-red-50 hover:bg-red-50' : '' }}"
                                data-channel="{{ $row['channel'] }}">
                                {{-- No --}}
                                <td class="px-4 py-3 text-center text-xs font-medium text-gray-400">{{ $row['no'] }}</td>

                                {{-- Waktu --}}
                                <td class="px-4 py-3">
                                    <span class="font-mono text-sm font-semibold {{ $isOnAir ? 'text-red-600' : 'text-gray-700' }}">
                                        {{ $row['waktu'] }}
                                    </span>
                                </td>

                                {{-- Mata Acara --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        {{-- Thumbnail --}}
                                        @if($row['thumbnail'])
                                            <img src="{{ asset('images/thumbnails/' . $row['thumbnail']) }}"
                                                 alt="{{ $row['acara'] }}"
                                                 class="w-10 h-10 rounded-lg object-cover flex-shrink-0 shadow-sm">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <iconify-icon icon="heroicons:tv" class="text-gray-300" width="18"></iconify-icon>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm leading-tight">{{ $row['acara'] }}</p>
                                            @if($isOnAir)
                                                <span class="text-red-500 text-[10px] font-bold flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full live-pulse inline-block"></span>
                                                    Sedang Tayang
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Channel --}}
                                <td class="px-4 py-3">
                                    <span class="text-xs font-medium px-2 py-1 rounded-md {{ $row['channel'] === 'Nasional' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $row['channel'] }}
                                    </span>
                                </td>

                                {{-- Durasi --}}
                                <td class="px-4 py-3 text-xs text-gray-500 font-mono">{{ $row['durasi'] }}</td>

                                {{-- Status --}}
                                <td class="px-4 py-3">
                                    @if($row['status'] === 'on_air')
                                        <span class="inline-flex items-center gap-1 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full live-pulse">
                                            <iconify-icon icon="heroicons:signal" width="11"></iconify-icon>
                                            ON AIR
                                        </span>
                                    @elseif($row['status'] === 'sudah_tayang')
                                        <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-500 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            <iconify-icon icon="heroicons:check-circle" width="11"></iconify-icon>
                                            SELESAI
                                        </span>
                                    @elseif($row['status'] === 'batal')
                                        <span class="inline-flex items-center gap-1 bg-red-100 text-red-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            <iconify-icon icon="heroicons:x-circle" width="11"></iconify-icon>
                                            BATAL
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-600 text-xs font-semibold px-2.5 py-1 rounded-full border border-amber-200">
                                            <iconify-icon icon="heroicons:clock" width="11"></iconify-icon>
                                            MENUNGGU
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Footer tabel --}}
            <div class="px-5 py-3 border-t border-gray-50 bg-gray-50/50">
                <p class="text-xs text-gray-400 flex items-center gap-1">
                    <iconify-icon icon="heroicons:information-circle" width="13"></iconify-icon>
                    Data jadwal diperbarui secara otomatis setiap hari. Jadwal dapat berubah sewaktu-waktu.
                </p>
            </div>
        </div>
    </div>

    {{-- ===== SIDEBAR KANAN ===== --}}
    <div class="w-full lg:w-72 xl:w-80 space-y-4 flex-shrink-0">

        {{-- Program Berikutnya --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-4 pt-4 pb-2 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <iconify-icon icon="heroicons:queue-list" class="text-blue-700" width="18"></iconify-icon>
                    Program Berikutnya
                </h3>
            </div>
            <div class="divide-y divide-gray-50">
                @php
                    $nextPrograms = [
                        ['waktu' => '10:00', 'acara' => 'INI BORNEO',      'thumbnail' => 'ini_borneo_thumb.jpg',  'channel' => 'Kalsel'],
                        ['waktu' => '15:00', 'acara' => 'PERSPEKTIF',      'thumbnail' => 'perspektif_thumb.jpg',  'channel' => 'Kalsel'],
                        ['waktu' => '16:00', 'acara' => 'KALSEL HARI INI', 'thumbnail' => 'kalseltoday_thumb.jpg', 'channel' => 'Kalsel'],
                        ['waktu' => '17:00', 'acara' => 'KAJIAN TAUHID',   'thumbnail' => 'kajian_thumb.jpg',      'channel' => 'Kalsel'],
                        ['waktu' => '18:00', 'acara' => 'ADZAN MAGHRIB',   'thumbnail' => 'adzan_thumb.jpg',       'channel' => 'Kalsel'],
                    ];
                @endphp
                @foreach($nextPrograms as $prog)
                    <div class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                        <img src="{{ asset('images/thumbnails/' . $prog['thumbnail']) }}"
                             alt="{{ $prog['acara'] }}"
                             class="w-12 h-12 rounded-xl object-cover flex-shrink-0 shadow-sm">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-800 truncate">{{ $prog['acara'] }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5 flex items-center gap-1">
                                <iconify-icon icon="heroicons:clock" width="11"></iconify-icon>
                                {{ $prog['waktu'] }} WIB
                            </p>
                        </div>
                        <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded {{ $prog['channel'] === 'Nasional' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600' }} flex-shrink-0">
                            {{ $prog['channel'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Statistik Siaran --}}
        <div class="bg-white rounded-2xl shadow-sm p-4">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2 mb-3">
                <iconify-icon icon="heroicons:chart-bar" class="text-blue-700" width="18"></iconify-icon>
                Statistik Siaran
            </h3>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-blue-50 rounded-xl p-3 text-center">
                    <iconify-icon icon="heroicons:tv" class="text-blue-600 mx-auto" width="22"></iconify-icon>
                    <p class="text-2xl font-extrabold text-blue-700 mt-1">{{ count($dummyData) }}</p>
                    <p class="text-[11px] text-blue-500 font-medium">Total Acara</p>
                </div>
                <div class="bg-red-50 rounded-xl p-3 text-center">
                    <iconify-icon icon="heroicons:signal" class="text-red-500 mx-auto live-pulse" width="22"></iconify-icon>
                    <p class="text-2xl font-extrabold text-red-600 mt-1">1</p>
                    <p class="text-[11px] text-red-400 font-medium">Live Sekarang</p>
                </div>
                <div class="bg-amber-50 rounded-xl p-3 text-center">
                    <iconify-icon icon="heroicons:clock" class="text-amber-500 mx-auto" width="22"></iconify-icon>
                    <p class="text-2xl font-extrabold text-amber-600 mt-1">10</p>
                    <p class="text-[11px] text-amber-400 font-medium">Menunggu</p>
                </div>
                <div class="bg-green-50 rounded-xl p-3 text-center">
                    <iconify-icon icon="heroicons:check-circle" class="text-green-500 mx-auto" width="22"></iconify-icon>
                    <p class="text-2xl font-extrabold text-green-600 mt-1">3</p>
                    <p class="text-[11px] text-green-400 font-medium">Selesai</p>
                </div>
            </div>
        </div>

        {{-- Banner TVRI Kalsel --}}
        <div class="rounded-2xl overflow-hidden shadow-sm relative h-36"
             style="background: linear-gradient(135deg, #001f5b, #003d99);">
            <img src="{{ asset('images/backgrounds/mainbg2.png') }}"
                 alt="TVRI Kalsel"
                 class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-luminosity">
            <div class="relative z-10 flex flex-col items-center justify-center h-full text-center p-4">
                <img src="{{ asset('images/logo/logowhite.png') }}" alt="TVRI" class="h-8 w-auto mb-2">
                <p class="text-white text-xs font-semibold">Melayani Informasi</p>
                <p class="text-blue-200 text-xs">untuk Negeri</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Filter tab functionality
    function filterTab(tab) {
        // Update tab active state
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('tab-active', 'border-blue-900');
            btn.classList.add('border-gray-200', 'text-gray-600');
        });
        const activeBtn = document.querySelector(`[data-tab="${tab}"]`);
        activeBtn.classList.add('tab-active', 'border-blue-900');
        activeBtn.classList.remove('border-gray-200', 'text-gray-600');

        // Filter rows
        const rows = document.querySelectorAll('#rundownTable tr');
        rows.forEach(row => {
            const channel = row.getAttribute('data-channel');
            if (tab === 'Semua') {
                row.style.display = '';
            } else if (tab === 'Nasional' && channel === 'Nasional') {
                row.style.display = '';
            } else if (tab === 'Kalsel' && channel === 'Kalsel') {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush
