@extends('layouts.public')

@section('title', 'Rundown Acara Hari Ini')

@section('content')

{{-- GRID UTAMA: Konten kiri + Sidebar kanan --}}
<div class="flex flex-col lg:flex-row gap-5 items-start">

    {{-- ===== KOLOM KIRI ===== --}}
    <div class="flex-1 min-w-0 space-y-5 w-full">

        {{-- HERO: Program Sedang Tayang --}}
        <div class="relative rounded-2xl overflow-hidden shadow-sm border border-blue-900/10 min-h-[210px] bg-[#0c2461]">

            {{-- Background utama: bannertop.png --}}
            <img src="{{ asset('images/backgrounds/bannertop.png') }}"
                 alt="Banner Background"
                 class="absolute inset-0 w-full h-full object-cover object-right">

            {{-- Overlay gradient biru halus di sisi kiri agar teks kontras dan sisi kanan gedung TVRI tetap terlihat jelas --}}
            <div class="absolute inset-0" style="background: linear-gradient(90deg, rgba(12, 36, 97, 0.90) 0%, rgba(12, 36, 97, 0.78) 45%, rgba(12, 36, 97, 0.20) 70%, transparent 100%);"></div>

            <div class="relative z-10 p-4 sm:p-5 flex flex-col md:flex-row items-center gap-4 sm:gap-5 h-full">

                {{-- THUMBNAIL KIRI: Habar Banua Box --}}
                <div class="w-full md:w-[240px] lg:w-[250px] h-[155px] sm:h-[170px] rounded-2xl overflow-hidden shadow-md border border-white/20 flex-shrink-0 bg-sky-400">
                    <img id="hero-thumbnail"
                         src="{{ asset('images/thumbnails/' . ($currentOnAir['thumbnail'] ?? 'habarbanua.jpg')) }}"
                         alt="{{ $currentOnAir['acara'] ?? 'Habar Banua' }}"
                         class="w-full h-full object-cover">
                </div>

                {{-- INFO TENGAH --}}
                <div class="flex-1 min-w-0 text-white flex flex-col justify-center max-w-md">
                    {{-- Tag SEDANG TAYANG --}}
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-white text-[11px] font-semibold w-fit mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-300 inline-block animate-pulse"></span>
                        SEDANG TAYANG
                    </div>

                    {{-- Judul Acara --}}
                    <h1 id="hero-title" class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-none mb-1">
                        {{ $currentOnAir['acara'] ?? 'HABAR BANUA' }}
                    </h1>
                    <p class="text-white/90 text-sm font-medium mb-3">
                        {{ $currentOnAir['kategori'] ?? 'Kalimantan Selatan' }}
                    </p>

                    {{-- Waktu & Badge Live --}}
                    <div class="flex items-center gap-2 mb-2">
                        <iconify-icon icon="lucide:clock" class="text-white/90" width="16"></iconify-icon>
                        <span id="hero-time" class="text-white font-bold text-sm tracking-wide">{{ $currentOnAir['waktu'] ?? '09:01 - 10:00' }}</span>
                        @if(($currentOnAir['status_type'] ?? '') === 'live')
                            <span class="bg-[#d90429] text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider live-pulse">LIVE</span>
                        @endif
                    </div>

                    {{-- Deskripsi --}}
                    <p class="text-white/80 text-xs leading-relaxed">
                        {{ $currentOnAir['description'] ?? 'Menghadirkan informasi terkini seputar Kalimantan Selatan, dengan beragam berita dan kabar terbaru dari masyarakat Banua.' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- TABEL RUNDOWN --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 overflow-hidden">

            {{-- Header Tabel --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4">
                <div class="flex items-center gap-3">
                    <div class="text-blue-600 flex-shrink-0">
                        <iconify-icon icon="lucide:calendar-days" width="30"></iconify-icon>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800 leading-tight">Rundown Acara Hari ini</h2>
                        <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                    </div>
                </div>

                {{-- Filter Tabs --}}
                <div class="flex flex-wrap items-center gap-1.5" id="filterTabs">
                    @php
                        $tabs = ['Semua', 'Nasional', 'Kalsel', 'Talkshow', 'Religi', 'Dokumenter'];
                    @endphp
                    @foreach($tabs as $tab)
                        <button onclick="filterTab('{{ $tab }}')"
                                data-tab="{{ $tab }}"
                                class="tab-btn px-4 py-1.5 rounded-full text-xs font-semibold transition-all {{ $loop->first ? 'bg-[#0066ff] text-white shadow-xs' : 'text-slate-600 hover:text-blue-600' }}">
                            {{ $tab }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100">
                            <th class="py-2.5 px-3 text-xs font-semibold text-slate-500 text-center w-10">No</th>
                            <th class="py-2.5 px-3 text-xs font-semibold text-slate-500 text-left w-20">Waktu</th>
                            <th class="py-2.5 px-3 text-xs font-semibold text-slate-500 text-left">Mata Acara</th>
                            <th class="py-2.5 px-3 text-xs font-semibold text-slate-500 text-left w-20">Channel</th>
                            <th class="py-2.5 px-3 text-xs font-semibold text-slate-500 text-left w-20">Durasi</th>
                            <th class="py-2.5 px-3 text-xs font-semibold text-slate-500 text-left w-24">Info</th>
                            <th class="py-2.5 px-3 text-xs font-semibold text-slate-500 text-center w-24">Status</th>
                        </tr>
                    </thead>
                    <tbody id="rundownTable">
                        @php
                            $dummyData = [
                                [
                                    'no' => 1,
                                    'waktu' => '09:01:00',
                                    'acara' => 'HABAR BANUA',
                                    'kategori' => 'Berita',
                                    'thumbnail' => 'habarbanua.jpg',
                                    'channel' => 'Kalsel',
                                    'durasi' => '0:59:00',
                                    'info' => 'ON AIR',
                                    'status' => 'LIVE',
                                    'status_type' => 'live'
                                ],
                                [
                                    'no' => 2,
                                    'waktu' => '10:00:30',
                                    'acara' => 'INI BORNEO',
                                    'kategori' => 'Wisata & Budaya',
                                    'thumbnail' => 'ini_borneo_thumb.jpg',
                                    'channel' => 'Kalsel',
                                    'durasi' => '0:59:00',
                                    'info' => 'MENUNGGU',
                                    'status' => 'RELAY',
                                    'status_type' => 'relay'
                                ],
                                [
                                    'no' => 3,
                                    'waktu' => '15:00:30',
                                    'acara' => 'PERSPEKTIF',
                                    'kategori' => 'Talkshow',
                                    'thumbnail' => 'perspektif_thumb.jpg',
                                    'channel' => 'Kalsel',
                                    'durasi' => '0:59:00',
                                    'info' => 'MENUNGGU',
                                    'status' => 'RELAY',
                                    'status_type' => 'relay'
                                ],
                                [
                                    'no' => 4,
                                    'waktu' => '17:00:00',
                                    'acara' => 'KALSEL HARI INI',
                                    'kategori' => 'Berita',
                                    'thumbnail' => 'kalseltoday_thumb.jpg',
                                    'channel' => 'Kalsel',
                                    'durasi' => '0:30:00',
                                    'info' => 'MENUNGGU',
                                    'status' => 'LIVE',
                                    'status_type' => 'live'
                                ],
                                [
                                    'no' => 5,
                                    'waktu' => '17:30:00',
                                    'acara' => 'INSPIRASI INDONESIA',
                                    'kategori' => 'Dokumenter',
                                    'thumbnail' => 'inspirasiindo_thumb.PNG',
                                    'channel' => 'Kalsel',
                                    'durasi' => '0:15:00',
                                    'info' => 'MENUNGGU',
                                    'status' => 'RERUN',
                                    'status_type' => 'rerun'
                                ],
                                [
                                    'no' => 6,
                                    'waktu' => '17:45:00',
                                    'acara' => 'KAJIAN TAUHID',
                                    'kategori' => 'Religi',
                                    'thumbnail' => 'kajian_thumb.jpg',
                                    'channel' => 'Kalsel',
                                    'durasi' => '0:15:00',
                                    'info' => 'MENUNGGU',
                                    'status' => 'PLAYBACK',
                                    'status_type' => 'playback'
                                ],
                                [
                                    'no' => 7,
                                    'waktu' => '18:00:00',
                                    'acara' => 'ADZAN MAGHRIB',
                                    'kategori' => 'Religi',
                                    'thumbnail' => 'adzan_thumb.jpg',
                                    'channel' => 'Kalsel',
                                    'durasi' => '0:05:00',
                                    'info' => 'MENUNGGU',
                                    'status' => 'PLAYBACK',
                                    'status_type' => 'playback'
                                ],
                                [
                                    'no' => 8,
                                    'waktu' => '18:05:00',
                                    'acara' => 'KAJIAN TAUHID (LANJUTAN)',
                                    'kategori' => 'Religi',
                                    'thumbnail' => 'kajian_thumb.jpg',
                                    'channel' => 'Kalsel',
                                    'durasi' => '0:26:00',
                                    'info' => 'MENUNGGU',
                                    'status' => 'PLAYBACK',
                                    'status_type' => 'playback'
                                ],
                                [
                                    'no' => 9,
                                    'waktu' => '18:31:00',
                                    'acara' => 'PESONA INDONESIA - KALSEL',
                                    'kategori' => 'Dokumenter',
                                    'thumbnail' => 'pesonaindo_thumb.PNG',
                                    'channel' => 'Kalsel',
                                    'durasi' => '0:30:00',
                                    'info' => 'MENUNGGU',
                                    'status' => 'PLAYBACK',
                                    'status_type' => 'playback'
                                ],
                                [
                                    'no' => 10,
                                    'waktu' => '19:01:00',
                                    'acara' => 'MAWARUNG',
                                    'kategori' => 'Talkshow',
                                    'thumbnail' => 'mawarung_thumb.jpg',
                                    'channel' => 'Kalsel',
                                    'durasi' => '0:59:00',
                                    'info' => 'MENUNGGU',
                                    'status' => 'PLAYBACK',
                                    'status_type' => 'playback'
                                ],
                            ];
                            $tableData = $programs ?? $dummyData;
                        @endphp

                        @foreach($tableData as $row)
                            <tr class="border-b border-slate-100/80 hover:bg-blue-50/20 transition-colors"
                                data-channel="{{ $row['channel'] }}"
                                data-category="{{ $row['kategori'] }}">

                                {{-- No --}}
                                <td class="px-3 py-2.5 text-center text-xs font-semibold text-slate-700">{{ $row['no'] }}</td>

                                {{-- Waktu --}}
                                <td class="px-3 py-2.5 text-xs font-semibold text-slate-700 whitespace-nowrap">{{ $row['waktu'] }}</td>

                                {{-- Mata Acara --}}
                                <td class="px-3 py-2.5">
                                    <div class="flex items-center gap-2.5">
                                        @if($row['thumbnail'])
                                            <img src="{{ asset('images/thumbnails/' . $row['thumbnail']) }}"
                                                 alt="{{ $row['acara'] }}"
                                                 class="w-10 h-6 sm:w-11 sm:h-7 rounded object-cover flex-shrink-0 shadow-xs border border-slate-200/50">
                                        @else
                                            <div class="w-10 h-6 sm:w-11 sm:h-7 rounded bg-slate-100 flex items-center justify-center flex-shrink-0">
                                                <iconify-icon icon="lucide:tv" class="text-slate-400" width="14"></iconify-icon>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-slate-900 text-xs leading-tight">{{ $row['acara'] }}</p>
                                            <p class="text-[11px] text-slate-400 font-normal leading-tight mt-0.5">{{ $row['kategori'] }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Channel --}}
                                <td class="px-3 py-2.5 text-xs font-medium text-slate-700">{{ $row['channel'] }}</td>

                                {{-- Durasi --}}
                                <td class="px-3 py-2.5 text-xs text-slate-700 font-normal whitespace-nowrap">{{ $row['durasi'] }}</td>

                                {{-- Info --}}
                                <td class="px-3 py-2.5 text-xs font-bold text-slate-800 whitespace-nowrap">{{ $row['info'] }}</td>

                                {{-- Status --}}
                                <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                    @if($row['status_type'] === 'live')
                                        <span class="inline-block bg-[#d90429] text-white text-[10px] font-extrabold px-3 py-0.5 rounded-full uppercase tracking-wider shadow-xs">
                                            LIVE
                                        </span>
                                    @elseif($row['status_type'] === 'relay')
                                        <span class="inline-block bg-[#1d4ed8] text-white text-[10px] font-extrabold px-3 py-0.5 rounded-full uppercase tracking-wider shadow-xs">
                                            RELAY
                                        </span>
                                    @elseif($row['status_type'] === 'rerun')
                                        <span class="inline-block bg-[#eab308] text-white text-[10px] font-extrabold px-3 py-0.5 rounded-full uppercase tracking-wider shadow-xs">
                                            RERUN
                                        </span>
                                    @else
                                        <span class="inline-block bg-[#16a34a] text-white text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-xs">
                                            PLAYBACK
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Footer / Pagination Dots di bawah tabel --}}
            <div class="pt-4 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-slate-400 inline-block"></span>
                <span class="w-2 h-2 rounded-full bg-slate-200 inline-block"></span>
                <span class="w-2 h-2 rounded-full bg-slate-200 inline-block"></span>
                <span class="w-2 h-2 rounded-full bg-slate-200 inline-block"></span>
                <span class="w-2 h-2 rounded-full bg-slate-200 inline-block"></span>
            </div>
        </div>
    </div>

    {{-- ===== SIDEBAR KANAN ===== --}}
    <div class="w-full lg:w-[300px] xl:w-[320px] space-y-4 flex-shrink-0">

        {{-- Card 1: Program Berikutnya --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <div class="flex items-center gap-2 mb-3">
                <iconify-icon icon="lucide:clock" class="text-blue-700" width="16"></iconify-icon>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">PROGRAM BERIKUTNYA</h3>
            </div>
            <div class="space-y-3">
                @php
                    $nextPrograms = [
                        ['waktu' => '10:00:30', 'acara' => 'INI BORNEO',      'kategori' => 'Wisata & Budaya', 'thumbnail' => 'ini_borneo_thumb.jpg'],
                        ['waktu' => '15:00:30', 'acara' => 'PERSPEKTIF',      'kategori' => 'Talkshow',        'thumbnail' => 'perspektif_thumb.jpg'],
                        ['waktu' => '17:00:00', 'acara' => 'KALSEL HARI INI', 'kategori' => 'Berita',          'thumbnail' => 'kalseltoday_thumb.jpg'],
                        ['waktu' => '18:00:00', 'acara' => 'KAJIAN TAUHID',   'kategori' => 'Religi',          'thumbnail' => 'kajian_thumb.jpg'],
                        ['waktu' => '18:00:00', 'acara' => 'ADZAN MAGHRIB',   'kategori' => 'Religi',          'thumbnail' => 'adzan_thumb.jpg'],
                    ];
                @endphp
                @foreach($nextPrograms as $prog)
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-9 rounded-lg overflow-hidden flex-shrink-0 shadow-xs border border-slate-100">
                            <img src="{{ asset('images/thumbnails/' . $prog['thumbnail']) }}"
                                 alt="{{ $prog['acara'] }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] text-slate-400 font-medium leading-none mb-1">{{ $prog['waktu'] }}</p>
                            <p class="text-xs font-extrabold text-[#1e3a8a] leading-tight truncate">{{ $prog['acara'] }}</p>
                            <p class="text-[10px] text-slate-400 leading-none mt-1">{{ $prog['kategori'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Card 2: Statistik Siaran --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <div class="flex items-center gap-2 mb-3">
                <iconify-icon icon="lucide:bar-chart-2" class="text-blue-700" width="16"></iconify-icon>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">STATISTIK SIARAN</h3>
            </div>
            <div class="grid grid-cols-2 gap-2">
                {{-- Total Acara --}}
                <div class="bg-[#eff6ff] border border-blue-100/70 rounded-xl p-2.5 flex items-center justify-between">
                    <iconify-icon icon="lucide:tv" class="text-[#0066ff]" width="26"></iconify-icon>
                    <div class="text-right">
                        <span class="text-[10px] font-semibold text-blue-600 block leading-tight">Total Acara</span>
                        <span class="text-xl font-extrabold text-[#0066ff] leading-none">8</span>
                    </div>
                </div>

                {{-- Live --}}
                <div class="bg-[#fff1f2] border border-red-100/70 rounded-xl p-2.5 flex items-center justify-between">
                    <iconify-icon icon="lucide:radio" class="text-[#e11d48] live-pulse" width="26"></iconify-icon>
                    <div class="text-right">
                        <span class="text-[10px] font-semibold text-red-600 block leading-tight">Live</span>
                        <span class="text-xl font-extrabold text-[#e11d48] leading-none">2</span>
                    </div>
                </div>

                {{-- Selesai --}}
                <div class="bg-[#f0fdf4] border border-green-100/70 rounded-xl p-2.5 flex items-center justify-between">
                    <iconify-icon icon="lucide:check-circle-2" class="text-[#16a34a]" width="26"></iconify-icon>
                    <div class="text-right">
                        <span class="text-[10px] font-semibold text-green-600 block leading-tight">Selesai</span>
                        <span class="text-xl font-extrabold text-[#16a34a] leading-none">3</span>
                    </div>
                </div>

                {{-- Menunggu --}}
                <div class="bg-[#fffbeb] border border-amber-100/70 rounded-xl p-2.5 flex items-center justify-between">
                    <iconify-icon icon="lucide:clock" class="text-[#d97706]" width="26"></iconify-icon>
                    <div class="text-right">
                        <span class="text-[10px] font-semibold text-amber-600 block leading-tight">Menunggu</span>
                        <span class="text-xl font-extrabold text-[#d97706] leading-none">5</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Banner TVRI Kalsel --}}
        <div class="rounded-2xl overflow-hidden shadow-sm relative h-36 bg-gradient-to-r from-[#0038a8] via-[#004dc9] to-[#002673] flex items-center justify-between p-4">
            {{-- Soft glow overlay --}}
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-blue-300/20 via-transparent to-transparent pointer-events-none"></div>

            {{-- Sisi Kiri Teks --}}
            <div class="relative z-10 flex flex-col justify-center h-full max-w-[50%]">
                <div class="flex items-center gap-1.5 mb-2">
                    <img src="{{ asset('images/logo/logowhite.png') }}" alt="TVRI" style="height: 22px; width: auto; object-fit: contain;">
                    <span class="text-white font-extrabold text-sm tracking-wider">KALSEL</span>
                </div>
                <p class="text-white text-xs sm:text-sm font-bold leading-tight">Melayani Informasi</p>
                <p class="text-cyan-200 text-xs sm:text-sm font-semibold leading-tight mt-0.5">untuk Negeri</p>
            </div>

            {{-- Sisi Kanan Rumah Banjar --}}
            <div class="absolute right-0 top-0 bottom-0 w-[52%] overflow-hidden">
                <div class="absolute inset-y-0 left-0 w-12 z-10 bg-gradient-to-r from-[#004dc9] to-transparent"></div>
                <img src="{{ asset('images/backgrounds/banjar_house.jpg') }}" alt="TVRI Kalsel" class="w-full h-full object-cover object-center">
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
            btn.classList.remove('bg-[#0066ff]', 'text-white', 'shadow-xs');
            btn.classList.add('text-slate-600');
        });
        const activeBtn = document.querySelector(`[data-tab="${tab}"]`);
        if (activeBtn) {
            activeBtn.classList.add('bg-[#0066ff]', 'text-white', 'shadow-xs');
            activeBtn.classList.remove('text-slate-600');
        }

        // Filter rows
        const rows = document.querySelectorAll('#rundownTable tr');
        rows.forEach(row => {
            const channel = row.getAttribute('data-channel');
            const category = row.getAttribute('data-category');

            if (tab === 'Semua') {
                row.style.display = '';
            } else if (tab === 'Nasional' && channel === 'Nasional') {
                row.style.display = '';
            } else if (tab === 'Kalsel' && channel === 'Kalsel') {
                row.style.display = '';
            } else if (tab === 'Talkshow' && category && category.toLowerCase().includes('talkshow')) {
                row.style.display = '';
            } else if (tab === 'Religi' && category && category.toLowerCase().includes('religi')) {
                row.style.display = '';
            } else if (tab === 'Dokumenter' && category && category.toLowerCase().includes('dokumenter')) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush
