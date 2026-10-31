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
                    @if(!empty($currentOnAir['kategori']))
                        <p class="text-white/90 text-sm font-medium mb-3">
                            {{ $currentOnAir['kategori'] }}
                        </p>
                    @else
                        <div class="mb-2"></div>
                    @endif

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

            <style>
                .tabs-scroll-wrapper {
                    position: relative;
                    display: flex;
                    align-items: center;
                    max-width: 100%;
                }
                @media (min-width: 640px) {
                    .tabs-scroll-wrapper {
                        max-width: 62%;
                    }
                }
                .scroll-tabs-container {
                    display: flex !important;
                    flex-wrap: nowrap !important;
                    align-items: center;
                    gap: 0.375rem;
                    overflow-x: auto !important;
                    white-space: nowrap !important;
                    scrollbar-width: none;
                    -ms-overflow-style: none;
                    -webkit-overflow-scrolling: touch;
                    scroll-behavior: smooth;
                    padding-left: 2px;
                    padding-right: 2px;
                }
                .scroll-tabs-container::-webkit-scrollbar {
                    display: none !important;
                }
                .scroll-tabs-container button {
                    flex-shrink: 0 !important;
                    white-space: nowrap !important;
                }
                .tab-nav-btn {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    z-index: 20;
                    width: 28px;
                    height: 28px;
                    border-radius: 9999px;
                    background: rgba(255, 255, 255, 0.95);
                    backdrop-filter: blur(4px);
                    border: 1px solid rgba(226, 232, 240, 0.9);
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                    color: #475569;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                    opacity: 0;
                    pointer-events: none;
                    cursor: pointer;
                }
                .tabs-scroll-wrapper:hover .tab-nav-btn {
                    opacity: 1;
                    pointer-events: auto;
                }
                .tab-nav-btn:hover {
                    color: #0066ff;
                    background: #ffffff;
                    transform: translateY(-50%) scale(1.12);
                    box-shadow: 0 4px 12px rgba(0, 102, 255, 0.22);
                    border-color: #bfdbfe;
                }
                .tab-nav-btn:active {
                    transform: translateY(-50%) scale(0.95);
                }
                .tab-nav-left {
                    left: -10px;
                }
                .tab-nav-right {
                    right: -10px;
                }
            </style>

            {{-- PILIH HARI (SENIN - MINGGU) --}}
            <div class="mb-4">
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
                    @foreach($availableDays as $dayKey => $dayData)
                        <a href="{{ route('home', ['hari' => $dayKey]) }}"
                           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ $selectedDay === $dayKey ? 'bg-[#0066ff] text-white shadow-xs' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/80 hover:text-slate-900' }}">
                            <span>{{ $dayData['name'] }}</span>
                            @if($dayKey === $todayKey)
                                <span class="text-[9px] px-1.5 py-0.5 rounded-full font-black uppercase tracking-wider {{ $selectedDay === $dayKey ? 'bg-white text-[#0066ff]' : 'bg-emerald-500 text-white' }}">Hari Ini</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Header Tabel --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4">
                <div class="flex items-center gap-3 shrink-0">
                    <div class="text-blue-600 flex-shrink-0">
                        <iconify-icon icon="lucide:calendar-days" width="30"></iconify-icon>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800 leading-tight flex items-center gap-2">
                            <span>Rundown Acara {{ $availableDays[$selectedDay]['name'] ?? 'Hari Ini' }}</span>
                            @if($selectedDay === $todayKey)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white">Hari Ini</span>
                            @endif
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::now('Asia/Makassar')->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
                    </div>
                </div>

                {{-- Filter Tabs (Scroll ke Samping dengan Tombol Geser) --}}
                <div class="tabs-scroll-wrapper">
                    {{-- Tombol Geser Kiri --}}
                    <button type="button"
                            onclick="scrollTabs('left', 'filterTabs')"
                            class="tab-nav-btn tab-nav-left"
                            title="Geser ke kiri">
                        <iconify-icon icon="heroicons:chevron-left-20-solid" width="16"></iconify-icon>
                    </button>

                    <div class="scroll-tabs-container w-full" id="filterTabs">
                        @php
                            $assignedCategories = collect($programs ?? [])->pluck('kategori')->filter()->unique()->values()->toArray();
                            $tabs = array_merge(['Semua', 'Nasional', 'Kalsel'], $assignedCategories);
                        @endphp
                        @foreach($tabs as $tab)
                            <button onclick="filterTab('{{ $tab }}')"
                                    data-tab="{{ $tab }}"
                                    class="tab-btn px-4 py-1.5 rounded-full text-xs font-semibold transition-all cursor-pointer {{ $loop->first ? 'bg-[#0066ff] text-white shadow-xs' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                                {{ $tab }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Tombol Geser Kanan --}}
                    <button type="button"
                            onclick="scrollTabs('right', 'filterTabs')"
                            class="tab-nav-btn tab-nav-right"
                            title="Geser ke kanan">
                        <iconify-icon icon="heroicons:chevron-right-20-solid" width="16"></iconify-icon>
                    </button>
                </div>
            </div>

            {{-- Toolbar: Searchbar Acara Rundown --}}
            <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-t border-slate-100">
                <div class="relative w-full sm:w-72" style="max-width: 300px;">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <iconify-icon icon="heroicons:magnifying-glass-solid" width="16"></iconify-icon>
                    </div>
                    <input type="text"
                           id="rundownSearchInput"
                           placeholder="Cari acara siaran..."
                           class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/25 focus:border-blue-500 transition-all text-slate-800 placeholder-slate-400"
                           style="padding-left: 36px !important; padding-right: 32px !important; padding-top: 7px; padding-bottom: 7px;"
                           oninput="filterRundown()">
                    <button type="button"
                            id="clearRundownSearchBtn"
                            onclick="clearRundownSearch()"
                            class="hidden absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer">
                        <iconify-icon icon="heroicons:x-circle-solid" width="16"></iconify-icon>
                    </button>
                </div>

                <div class="text-[11px] text-slate-400 flex items-center gap-1.5 shrink-0">
                    <iconify-icon icon="heroicons:information-circle-solid" width="15" class="text-blue-500"></iconify-icon>
                    <span>Jadwal siaran dapat berubah sewaktu-waktu.</span>
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
                            $tableData = $programs ?? [];
                        @endphp

                        @foreach($tableData as $row)
                            @php
                                $rowModalData = array_merge($row, [
                                    'thumbnail_url' => !empty($row['thumbnail']) ? asset('images/thumbnails/' . $row['thumbnail']) : null,
                                    'description' => !empty($row['description']) ? $row['description'] : 'Menghadirkan tayangan berkualitas dan informasi terkini dari TVRI Kalimantan Selatan untuk masyarakat Banua.'
                                ]);
                            @endphp
                            <tr class="rundown-row border-b border-slate-100/80 hover:bg-blue-50/50 transition-all cursor-pointer group"
                                data-channel="{{ $row['channel'] }}"
                                data-category="{{ $row['kategori'] ?? '' }}"
                                data-title="{{ $row['acara'] }}"
                                onclick='openProgramDetailModal(@json($rowModalData))'
                                title="Klik untuk melihat deskripsi dan jam tayang acara">

                                {{-- No --}}
                                <td class="px-3 py-2.5 text-center text-xs font-semibold text-slate-700">{{ $row['no'] }}</td>

                                {{-- Waktu --}}
                                <td class="px-3 py-2.5 text-xs font-bold text-[#0052cc] whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <iconify-icon icon="lucide:clock" width="13" class="text-blue-500"></iconify-icon>
                                        <span>{{ $row['waktu'] }}</span>
                                    </div>
                                </td>

                                {{-- Mata Acara --}}
                                <td class="px-3 py-2.5">
                                    <div class="flex items-center gap-2.5">
                                        @if(!empty($row['thumbnail']))
                                            <img src="{{ asset('images/thumbnails/' . $row['thumbnail']) }}"
                                                 alt="{{ $row['acara'] }}"
                                                 class="w-10 h-6 sm:w-11 sm:h-7 rounded object-cover flex-shrink-0 shadow-xs border border-slate-200/50 group-hover:scale-105 transition-transform">
                                        @else
                                            <div class="w-10 h-6 sm:w-11 sm:h-7 rounded bg-slate-100 flex items-center justify-center flex-shrink-0">
                                                <iconify-icon icon="lucide:tv" class="text-slate-400" width="14"></iconify-icon>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-900 text-xs leading-tight group-hover:text-[#0052cc] transition-colors flex items-center gap-1.5 truncate">
                                                <span>{{ $row['acara'] }}</span>
                                                <iconify-icon icon="heroicons:arrow-top-right-on-square" width="12" class="opacity-0 group-hover:opacity-70 text-blue-500 transition-opacity shrink-0"></iconify-icon>
                                            </p>
                                            @if(!empty($row['kategori']))
                                                <p class="text-[11px] text-slate-400 font-normal leading-tight mt-0.5">{{ $row['kategori'] }}</p>
                                            @endif
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
                        {{-- Baris Pesan Kosong Saat Hasil Pencarian Tidak Ditemukan --}}
                        <tr id="emptyRundownSearchRow" class="hidden">
                            <td colspan="7" class="py-12 px-4 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <iconify-icon icon="heroicons:magnifying-glass" width="36" class="text-slate-300 mb-2"></iconify-icon>
                                    <p class="font-bold text-slate-600 text-sm">Tidak ada jadwal acara yang cocok</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian lain atau pilih tab filter lain</p>
                                </div>
                            </td>
                        </tr>
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
                    $nextPrograms = collect($tableData)
                        ->filter(function($p) {
                            $title = strtoupper($p['acara'] ?? '');
                            return !in_array($title, ['STING', 'PSA', 'TINJAUAN ACARA', 'PSA & STING', 'PSA / STING', 'PSA DAN STING']);
                        })
                        ->take(5);
                @endphp
                @foreach($nextPrograms as $prog)
                    @php
                        $progModalData = array_merge($prog, [
                            'thumbnail_url' => !empty($prog['thumbnail']) ? asset('images/thumbnails/' . $prog['thumbnail']) : null,
                            'description' => !empty($prog['description']) ? $prog['description'] : 'Program siaran pilihan TVRI Kalimantan Selatan.'
                        ]);
                    @endphp
                    <div class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-blue-50/60 transition cursor-pointer group"
                         onclick='openProgramDetailModal(@json($progModalData))'
                         title="Klik untuk melihat deskripsi & jam tayang">
                        <div class="w-14 h-9 rounded-lg overflow-hidden flex-shrink-0 shadow-xs border border-slate-100 group-hover:scale-105 transition-transform bg-slate-100 flex items-center justify-center">
                            @if(!empty($prog['thumbnail']))
                                <img src="{{ asset('images/thumbnails/' . $prog['thumbnail']) }}"
                                     alt="{{ $prog['acara'] }}"
                                     class="w-full h-full object-cover">
                            @else
                                <iconify-icon icon="lucide:tv" class="text-slate-400" width="18"></iconify-icon>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] text-slate-400 font-medium leading-none mb-1 flex items-center gap-1">
                                <iconify-icon icon="lucide:clock" width="11" class="text-blue-500"></iconify-icon>
                                <span>{{ $prog['waktu'] }}</span>
                            </p>
                            <p class="text-xs font-extrabold text-[#1e3a8a] group-hover:text-blue-600 transition-colors leading-tight truncate">{{ $prog['acara'] }}</p>
                            <p class="text-[10px] text-slate-400 leading-none mt-1">{{ $prog['kategori'] ?? '' }}</p>
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
                        <span class="text-xl font-extrabold text-[#0066ff] leading-none">{{ count($tableData) }}</span>
                    </div>
                </div>

                {{-- Live --}}
                <div class="bg-[#fff1f2] border border-red-100/70 rounded-xl p-2.5 flex items-center justify-between">
                    <iconify-icon icon="lucide:radio" class="text-[#e11d48] live-pulse" width="26"></iconify-icon>
                    <div class="text-right">
                        <span class="text-[10px] font-semibold text-red-600 block leading-tight">Live</span>
                        <span class="text-xl font-extrabold text-[#e11d48] leading-none">{{ collect($tableData)->where('status_type', 'live')->count() }}</span>
                    </div>
                </div>

                {{-- Selesai --}}
                <div class="bg-[#f0fdf4] border border-green-100/70 rounded-xl p-2.5 flex items-center justify-between">
                    <iconify-icon icon="lucide:check-circle-2" class="text-[#16a34a]" width="26"></iconify-icon>
                    <div class="text-right">
                        <span class="text-[10px] font-semibold text-green-600 block leading-tight">Selesai</span>
                        <span class="text-xl font-extrabold text-[#16a34a] leading-none">{{ collect($tableData)->where('info', 'SUDAH TAYANG')->count() }}</span>
                    </div>
                </div>

                {{-- Menunggu --}}
                <div class="bg-[#fffbeb] border border-amber-100/70 rounded-xl p-2.5 flex items-center justify-between">
                    <iconify-icon icon="lucide:clock" class="text-[#d97706]" width="26"></iconify-icon>
                    <div class="text-right">
                        <span class="text-[10px] font-semibold text-amber-600 block leading-tight">Menunggu</span>
                        <span class="text-xl font-extrabold text-[#d97706] leading-none">{{ collect($tableData)->where('info', 'MENUNGGU')->count() }}</span>
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

{{-- ============================================================== --}}
{{-- MODAL DETAIL ACARA (JAM TAYANG & DESKRIPSI) --}}
{{-- ============================================================== --}}
<div id="programDetailModal"
     class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4 transition-all duration-200"
     onclick="closeProgramDetailModal()">
    <div class="bg-white rounded-[2rem] max-w-lg w-full overflow-hidden shadow-2xl border border-slate-100 transform transition-all duration-200 scale-95 opacity-0"
         id="programDetailModalContent"
         onclick="event.stopPropagation()">
        
        {{-- Banner & Thumbnail Header --}}
        <div class="relative h-44 sm:h-52 w-full bg-[#0c2461] overflow-hidden flex items-center justify-center">
            <img id="modalProgThumbnail"
                 src=""
                 alt=""
                 class="w-full h-full object-cover">
            
            <div id="modalProgThumbnailFallback" class="hidden w-full h-full bg-gradient-to-br from-[#0c2461] to-[#0052cc] flex items-center justify-center">
                <iconify-icon icon="lucide:tv" class="text-white/40" width="64"></iconify-icon>
            </div>

            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent"></div>

            {{-- Badges di Bagian Bawah Gambar --}}
            <div class="absolute bottom-3.5 left-4 right-4 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span id="modalProgCategory" class="px-3 py-1 rounded-full bg-[#0052cc] text-white text-xs font-black backdrop-blur-xs shadow-xs"></span>
                    <span id="modalProgChannel" class="px-2.5 py-0.5 rounded-full bg-white/20 text-white text-[11px] font-bold backdrop-blur-xs"></span>
                </div>
                <span id="modalProgStatus" class="text-white text-[10px] font-extrabold px-3 py-0.5 rounded-full uppercase tracking-wider shadow-xs"></span>
            </div>
        </div>

        {{-- Konten Detail Acara --}}
        <div class="p-5 sm:p-6 space-y-4">
            {{-- Judul Acara --}}
            <div>
                <h3 id="modalProgTitle" class="text-xl sm:text-2xl font-black text-[#002b66] tracking-tight leading-snug"></h3>
            </div>

            {{-- Info Jam Tayang & Durasi --}}
            <div class="grid grid-cols-2 gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100/70 text-[#0052cc] flex items-center justify-center shrink-0">
                        <iconify-icon icon="lucide:clock" width="20"></iconify-icon>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Jam Tayang</span>
                        <span id="modalProgTime" class="text-sm sm:text-base font-black text-slate-800 block"></span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100/70 text-indigo-600 flex items-center justify-center shrink-0">
                        <iconify-icon icon="lucide:timer" width="20"></iconify-icon>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Durasi</span>
                        <span id="modalProgDuration" class="text-sm sm:text-base font-black text-slate-800 block"></span>
                    </div>
                </div>
            </div>

            {{-- Info Tambahan: Produser / Host / Narasumber jika ada di spreadsheet --}}
            <div id="modalProgCredits" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs p-3 rounded-2xl bg-blue-50/50 border border-blue-100/70">
                <div id="modalProgHostWrap" class="hidden">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Pembawa Acara</span>
                    <span id="modalProgHost" class="font-bold text-slate-800"></span>
                </div>
                <div id="modalProgNarasumberWrap" class="hidden">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Narasumber</span>
                    <span id="modalProgNarasumber" class="font-bold text-slate-800"></span>
                </div>
                <div id="modalProgProduserWrap" class="hidden">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Produser</span>
                    <span id="modalProgProduser" class="font-bold text-slate-800"></span>
                </div>
                <div id="modalProgTemaWrap" class="hidden">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Tema Siaran</span>
                    <span id="modalProgTema" class="font-bold text-slate-800"></span>
                </div>
            </div>

            {{-- Deskripsi Acara --}}
            <div class="space-y-1.5">
                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <iconify-icon icon="heroicons:document-text-solid" width="16" class="text-[#0052cc]"></iconify-icon>
                    <span>Deskripsi Acara</span>
                </div>
                <div class="bg-slate-50/70 rounded-xl p-3.5 border border-slate-200/70">
                    <p id="modalProgDescription" class="text-xs sm:text-sm text-slate-700 leading-relaxed font-medium"></p>
                </div>
            </div>

            {{-- Footer Tombol Tutup --}}
            <div class="pt-2 flex justify-end">
                <button type="button"
                        onclick="closeProgramDetailModal()"
                        class="px-8 py-2 rounded-full border-2 border-[#ff0000] text-[#ff0000] hover:bg-[#ff0000] hover:text-white font-black text-xs tracking-wider transition-all duration-200 uppercase cursor-pointer shadow-2xs hover:shadow-sm">
                    TUTUP
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentRundownFilterTab = 'Semua';

    // Buka Modal Pop-Up Detail Acara (Jam Tayang & Deskripsi)
    function openProgramDetailModal(prog) {
        if (!prog) return;

        const modal = document.getElementById('programDetailModal');
        const content = document.getElementById('programDetailModalContent');
        if (!modal || !content) return;

        // Set Judul
        const titleEl = document.getElementById('modalProgTitle');
        if (titleEl) titleEl.textContent = prog.acara || 'Program Acara';

        // Set Credits (Host, Narasumber, Produser, Tema)
        const creditsEl = document.getElementById('modalProgCredits');
        let hasCredit = false;

        const setCreditItem = (wrapId, textId, val) => {
            const wrap = document.getElementById(wrapId);
            const text = document.getElementById(textId);
            if (wrap && text) {
                if (val && val !== '//' && val !== '-' && val.trim() !== '') {
                    text.textContent = val;
                    wrap.classList.remove('hidden');
                    hasCredit = true;
                } else {
                    wrap.classList.add('hidden');
                }
            }
        };

        setCreditItem('modalProgHostWrap', 'modalProgHost', prog.host);
        setCreditItem('modalProgNarasumberWrap', 'modalProgNarasumber', prog.narasumber);
        setCreditItem('modalProgProduserWrap', 'modalProgProduser', prog.produser);
        setCreditItem('modalProgTemaWrap', 'modalProgTema', prog.tema);

        if (creditsEl) {
            if (hasCredit) {
                creditsEl.classList.remove('hidden');
            } else {
                creditsEl.classList.add('hidden');
            }
        }

        // Set Kategori & Channel
        const catEl = document.getElementById('modalProgCategory');
        if (catEl) {
            if (prog.kategori && prog.kategori.trim() !== '') {
                catEl.textContent = prog.kategori;
                catEl.classList.remove('hidden');
            } else {
                catEl.classList.add('hidden');
            }
        }

        const chanEl = document.getElementById('modalProgChannel');
        if (chanEl) chanEl.textContent = prog.channel || 'Kalsel';

        // Set Jam Tayang & Durasi
        const timeEl = document.getElementById('modalProgTime');
        if (timeEl) timeEl.textContent = prog.waktu ? (prog.waktu + ' WITA') : '-';

        const durEl = document.getElementById('modalProgDuration');
        if (durEl) durEl.textContent = prog.durasi || '-';

        // Set Deskripsi Acara
        const descEl = document.getElementById('modalProgDescription');
        if (descEl) {
            descEl.textContent = prog.description || 'Menghadirkan tayangan berkualitas dan informasi terkini dari TVRI Kalimantan Selatan untuk masyarakat Banua.';
        }

        // Set Status Badge
        const statusEl = document.getElementById('modalProgStatus');
        if (statusEl) {
            const statusType = (prog.status_type || '').toLowerCase();
            statusEl.textContent = prog.status || 'PLAYBACK';

            if (statusType === 'live') {
                statusEl.className = 'text-white text-[10px] font-extrabold px-3 py-0.5 rounded-full uppercase tracking-wider shadow-xs bg-[#d90429]';
            } else if (statusType === 'relay') {
                statusEl.className = 'text-white text-[10px] font-extrabold px-3 py-0.5 rounded-full uppercase tracking-wider shadow-xs bg-[#1d4ed8]';
            } else if (statusType === 'rerun') {
                statusEl.className = 'text-white text-[10px] font-extrabold px-3 py-0.5 rounded-full uppercase tracking-wider shadow-xs bg-[#eab308]';
            } else {
                statusEl.className = 'text-white text-[10px] font-extrabold px-3 py-0.5 rounded-full uppercase tracking-wider shadow-xs bg-[#16a34a]';
            }
        }

        // Set Thumbnail
        const imgEl = document.getElementById('modalProgThumbnail');
        const fallbackEl = document.getElementById('modalProgThumbnailFallback');
        if (imgEl && fallbackEl) {
            if (prog.thumbnail_url) {
                imgEl.src = prog.thumbnail_url;
                imgEl.alt = prog.acara || '';
                imgEl.classList.remove('hidden');
                fallbackEl.classList.add('hidden');
            } else {
                imgEl.classList.add('hidden');
                fallbackEl.classList.remove('hidden');
            }
        }

        // Buka Modal & Kunci Scroll
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');

        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    // Tutup Modal Pop-Up Detail Acara
    function closeProgramDetailModal() {
        const modal = document.getElementById('programDetailModal');
        const content = document.getElementById('programDetailModalContent');
        if (!modal || !content) return;

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    // Event listener tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeProgramDetailModal();
        }
    });

    // Filter tab functionality
    function filterTab(tab) {
        currentRundownFilterTab = tab;

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

        // Terapkan filter gabungan
        filterRundown();
    }

    // Filter pencarian real-time + tab kategori pada tabel rundown siaran
    function filterRundown() {
        const searchInput = document.getElementById('rundownSearchInput');
        const clearBtn = document.getElementById('clearRundownSearchBtn');
        const query = (searchInput ? searchInput.value : '').toLowerCase().trim();

        // Tampilkan/sembunyikan tombol clear (X)
        if (clearBtn) {
            if (query.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }

        const rows = document.querySelectorAll('#rundownTable tr.rundown-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const channel  = (row.getAttribute('data-channel') || '').toLowerCase();
            const category = (row.getAttribute('data-category') || '').toLowerCase();
            const title    = (row.getAttribute('data-title') || '').toLowerCase();
            const rowText  = (row.textContent || '').toLowerCase();

            // 1. Cek kesesuaian Tab Kategori
            let matchTab = false;
            if (currentRundownFilterTab === 'Semua') {
                matchTab = true;
            } else if (currentRundownFilterTab === 'Nasional' && channel === 'nasional') {
                matchTab = true;
            } else if (currentRundownFilterTab === 'Kalsel' && channel === 'kalsel') {
                matchTab = true;
            } else if (category && (category === currentRundownFilterTab.toLowerCase() || category.includes(currentRundownFilterTab.toLowerCase()))) {
                matchTab = true;
            }

            // 2. Cek kesesuaian Kata Kunci Pencarian
            let matchSearch = true;
            if (query.length > 0) {
                matchSearch = title.includes(query) ||
                              category.includes(query) ||
                              channel.includes(query) ||
                              rowText.includes(query);
            }

            if (matchTab && matchSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Tampilkan/sembunyikan baris pesan kosong
        const emptyRow = document.getElementById('emptyRundownSearchRow');
        if (emptyRow) {
            if (visibleCount === 0) {
                emptyRow.classList.remove('hidden');
            } else {
                emptyRow.classList.add('hidden');
            }
        }
    }

    // Reset kolom pencarian rundown
    function clearRundownSearch() {
        const searchInput = document.getElementById('rundownSearchInput');
        if (searchInput) {
            searchInput.value = '';
            searchInput.focus();
        }
        filterRundown();
    }

    // Geser tab kategori ke kiri / kanan secara halus
    function scrollTabs(direction, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        const scrollDistance = 160;
        container.scrollBy({
            left: direction === 'left' ? -scrollDistance : scrollDistance,
            behavior: 'smooth'
        });
    }
</script>
@endpush
