@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

    {{-- Sub-header Halaman --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-[#0066ff] tracking-tight">Dashboard Admin</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola thumbnail, kategori, dan deskripsi acara siaran TVRI Kalimantan Selatan.</p>
        </div>

        {{-- Tombol Sinkronkan Spreadsheet --}}
        <form method="POST" action="{{ route('admin.programs.sync') }}">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition cursor-pointer shadow-xs active:scale-95">
                <iconify-icon icon="heroicons:arrow-path-solid" width="16"></iconify-icon>
                <span>Sinkronkan Spreadsheet</span>
            </button>
        </form>
    </div>

    {{-- Alert Status --}}
    @if(session('status'))
        <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2 shadow-xs">
            <iconify-icon icon="heroicons:check-circle-solid" width="18" class="text-emerald-600 shrink-0"></iconify-icon>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    {{-- BARIS ATAS: 4 Kartu Statistik (Kiri) + Hero Preview Live (Kanan) --}}
    <div class="flex flex-col lg:flex-row gap-4 items-stretch">
        
        {{-- KIRI: 4 Kartu Statistik --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 lg:w-[48%] flex-shrink-0 items-center">
            {{-- Total Acara --}}
            <div class="bg-white rounded-2xl p-3.5 border border-slate-200/80 shadow-xs flex items-center justify-between h-full">
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <iconify-icon icon="lucide:tv" width="24"></iconify-icon>
                </div>
                <div class="text-right">
                    <span class="text-[11px] font-bold text-slate-500 block leading-tight">Total Acara</span>
                    <span class="text-2xl font-black text-slate-800 leading-none mt-0.5 block">{{ $stats['total_acara'] ?? 8 }}</span>
                </div>
            </div>

            {{-- Live --}}
            <div class="bg-[#fff1f2] rounded-2xl p-3.5 border border-red-100 shadow-xs flex items-center justify-between h-full">
                <div class="w-11 h-11 rounded-xl bg-red-100/70 text-red-600 flex items-center justify-center shrink-0">
                    <iconify-icon icon="lucide:radio" width="24" class="animate-pulse"></iconify-icon>
                </div>
                <div class="text-right">
                    <span class="text-[11px] font-bold text-red-600 block leading-tight">Live</span>
                    <span class="text-2xl font-black text-red-600 leading-none mt-0.5 block">{{ $stats['total_live'] ?? 2 }}</span>
                </div>
            </div>

            {{-- Selesai --}}
            <div class="bg-[#f0fdf4] rounded-2xl p-3.5 border border-emerald-100 shadow-xs flex items-center justify-between h-full">
                <div class="w-11 h-11 rounded-xl bg-emerald-100/70 text-emerald-600 flex items-center justify-center shrink-0">
                    <iconify-icon icon="lucide:check-circle-2" width="24"></iconify-icon>
                </div>
                <div class="text-right">
                    <span class="text-[11px] font-bold text-emerald-700 block leading-tight">Selesai</span>
                    <span class="text-2xl font-black text-emerald-700 leading-none mt-0.5 block">{{ $stats['total_selesai'] ?? 3 }}</span>
                </div>
            </div>

            {{-- Menunggu --}}
            <div class="bg-[#fffbeb] rounded-2xl p-3.5 border border-amber-100 shadow-xs flex items-center justify-between h-full">
                <div class="w-11 h-11 rounded-xl bg-amber-100/70 text-amber-600 flex items-center justify-center shrink-0">
                    <iconify-icon icon="lucide:clock" width="24"></iconify-icon>
                </div>
                <div class="text-right">
                    <span class="text-[11px] font-bold text-amber-700 block leading-tight">Menunggu</span>
                    <span class="text-2xl font-black text-amber-700 leading-none mt-0.5 block">{{ $stats['total_menunggu'] ?? 5 }}</span>
                </div>
            </div>
        </div>

        {{-- KANAN: Hero Banner Acara Sedang Tayang Sekarang (Persis Seperti di Beranda) --}}
        <div class="lg:flex-1 relative rounded-2xl overflow-hidden shadow-xs border border-blue-900/10 min-h-[95px] bg-[#0c2461] flex items-center">
            {{-- Background banner --}}
            <img src="{{ asset('images/backgrounds/bannertop.png') }}" alt="Banner Background" class="absolute inset-0 w-full h-full object-cover object-right">
            <div class="absolute inset-0 bg-gradient-to-r from-[#0c2461]/95 via-[#0c2461]/85 to-transparent"></div>

            <div class="relative z-10 p-3 sm:p-4 flex items-center gap-4 w-full">
                {{-- Thumbnail Acara Sedang Tayang --}}
                <div class="w-28 sm:w-36 h-18 sm:h-20 rounded-xl overflow-hidden shadow-xs border border-white/20 shrink-0 bg-sky-500">
                    <img id="onair-banner-img"
                         src="{{ asset('images/thumbnails/' . ($currentOnAir['thumbnail'] ?? 'habarbanua.jpg')) }}"
                         alt="{{ $currentOnAir['acara'] ?? 'Habar Banua' }}"
                         class="w-full h-full object-cover">
                </div>

                {{-- Detail Acara Sedang Tayang --}}
                <div class="flex-1 min-w-0 text-white">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-white text-[9px] font-bold">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-300 inline-block animate-pulse"></span>
                            SEDANG TAYANG
                        </div>
                        @if(($currentOnAir['status_type'] ?? '') === 'live')
                            <span class="bg-[#d90429] text-white text-[9px] font-extrabold px-2 py-0.5 rounded-full tracking-wider uppercase live-pulse">LIVE</span>
                        @endif
                        <span id="onair-banner-time" class="text-white/90 text-xs font-bold">{{ $currentOnAir['waktu'] ?? '09:01:00' }}</span>
                    </div>
                    <h2 id="onair-banner-title" class="text-base sm:text-lg font-black text-white truncate leading-tight">
                        {{ $currentOnAir['acara'] ?? 'HABAR BANUA' }}
                    </h2>
                    <p id="onair-banner-category" class="text-cyan-200 text-xs font-semibold">
                        {{ $currentOnAir['kategori'] ?? '' }}
                    </p>
                    <p id="onair-banner-desc" class="text-white/80 text-[11px] line-clamp-1 mt-0.5">
                        {{ $currentOnAir['description'] ?? 'Menghadirkan informasi terkini seputar Kalimantan Selatan...' }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- BARIS UTAMA: Tabel Data Acara (Kiri) + Form Edit Detail Acara (Kanan) --}}
    <div class="flex flex-col lg:flex-row gap-5 items-start">

        {{-- KOLOM KIRI: Tabel Data Acara --}}
        <div class="flex-1 min-w-0 w-full bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6">

            <style>
                .tabs-scroll-wrapper {
                    position: relative;
                    display: flex;
                    align-items: center;
                    width: 100%;
                    overflow: hidden;
                    padding: 0 26px; /* ruang untuk tombol nav kiri & kanan */
                    box-sizing: border-box;
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
                    padding: 2px 4px;
                    min-width: 0;
                    flex: 1;
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
                    width: 24px;
                    height: 24px;
                    border-radius: 9999px;
                    background: rgba(255, 255, 255, 0.97);
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
                    flex-shrink: 0;
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
                    left: 0;
                }
                .tab-nav-right {
                    right: 0;
                }
            </style>
            
            {{-- PILIH HARI (SENIN - MINGGU) --}}
            <div class="mb-4">
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
                    @foreach($availableDays as $dayKey => $dayData)
                        <a href="{{ route('admin.dashboard', ['hari' => $dayKey]) }}"
                           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ $selectedDay === $dayKey ? 'bg-[#0066ff] text-white shadow-xs' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/80 hover:text-slate-900' }}">
                            <span>{{ $dayData['name'] }}</span>
                            @if($dayKey === $todayKey)
                                <span class="text-[9px] px-1.5 py-0.5 rounded-full font-black uppercase tracking-wider {{ $selectedDay === $dayKey ? 'bg-white text-[#0066ff]' : 'bg-emerald-500 text-white' }}">Hari Ini</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Header Tabel & Filter Tabs --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3 shrink-0">
                    <div class="text-blue-600 flex-shrink-0">
                        <iconify-icon icon="lucide:calendar-days" width="28"></iconify-icon>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-[#0c2461] leading-tight flex items-center gap-2">
                            <span>Data Acara ({{ $availableDays[$selectedDay]['name'] ?? 'Hari Ini' }})</span>
                            @if($selectedDay === $todayKey)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">Hari Ini</span>
                            @endif
                        </h2>
                    </div>
                </div>

                {{-- Filter Tabs (Scroll ke Samping dengan Tombol Geser) --}}
                <div class="tabs-scroll-wrapper min-w-0 flex-1">
                    {{-- Tombol Geser Kiri --}}
                    <button type="button"
                            onclick="scrollTabs('left', 'programFilterTabs')"
                            class="tab-nav-btn tab-nav-left"
                            title="Geser ke kiri">
                        <iconify-icon icon="heroicons:chevron-left-20-solid" width="16"></iconify-icon>
                    </button>

                    <div class="scroll-tabs-container w-full" id="programFilterTabs">
                        @php
                            $filterTabs = array_merge(['Semua', 'Nasional', 'Kalsel'], $categories ?? []);
                        @endphp
                        @foreach($filterTabs as $tab)
                            <button type="button"
                                    onclick="applyProgramFilter('{{ $tab }}')"
                                    data-tab="{{ $tab }}"
                                    class="prog-tab-btn px-3.5 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer {{ $tab === 'Semua' ? 'bg-[#0066ff] text-white shadow-xs' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                                {{ $tab }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Tombol Geser Kanan --}}
                    <button type="button"
                            onclick="scrollTabs('right', 'programFilterTabs')"
                            class="tab-nav-btn tab-nav-right"
                            title="Geser ke kanan">
                        <iconify-icon icon="heroicons:chevron-right-20-solid" width="16"></iconify-icon>
                    </button>
                </div>
            </div>

            {{-- Toolbar: Searchbar & Petunjuk --}}
            <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                {{-- Searchbar Input --}}
                <div class="relative w-full sm:w-72" style="max-width: 300px;">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <iconify-icon icon="heroicons:magnifying-glass-solid" width="16"></iconify-icon>
                    </div>
                    <input type="text"
                           id="adminProgramSearch"
                           placeholder="Cari acara..."
                           class="w-full text-xs bg-slate-50/90 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/25 focus:border-blue-500 transition-all text-slate-800 placeholder-slate-400"
                           style="padding-left: 36px !important; padding-right: 32px !important; padding-top: 7px; padding-bottom: 7px;"
                           oninput="filterAdminPrograms()">
                    <button type="button"
                            id="clearAdminSearchBtn"
                            onclick="clearAdminProgramSearch()"
                            class="hidden absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer">
                        <iconify-icon icon="heroicons:x-circle-solid" width="16"></iconify-icon>
                    </button>
                </div>

                {{-- Petunjuk Klik Acara --}}
                <div class="text-[11px] text-slate-400 flex items-center gap-1.5 shrink-0">
                    <iconify-icon icon="heroicons:cursor-arrow-rays" width="14" class="text-blue-500"></iconify-icon>
                    <span>Klik baris acara untuk mengedit detailnya.</span>
                </div>
            </div>

            {{-- Tabel Data Acara --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-3 text-center w-10">No</th>
                            <th class="py-3 px-3 w-20">Status</th>
                            <th class="py-3 px-3">Mata Acara</th>
                            <th class="py-3 px-3 w-20">Channel</th>
                            <th class="py-3 px-3 w-20">Durasi</th>
                            <th class="py-3 px-3 text-center w-24">Thumbnail</th>
                            <th class="py-3 px-3 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="adminProgramsTable" class="divide-y divide-slate-100">
                        @foreach($programs as $index => $row)
                            <tr class="program-row cursor-pointer transition-all hover:bg-blue-50/40 {{ $index === 0 ? 'bg-blue-50/60 ring-2 ring-blue-500/30 font-semibold' : '' }}"
                                data-id="{{ $row['id'] }}"
                                data-title="{{ $row['acara'] }}"
                                data-category="{{ $row['kategori'] }}"
                                data-channel="{{ $row['channel'] }}"
                                data-duration="{{ $row['durasi'] }}"
                                data-time="{{ $row['waktu'] }}"
                                data-thumbnail="{{ $row['thumbnail'] ?? '' }}"
                                data-thumb-url="{{ !empty($row['thumbnail']) ? asset('images/thumbnails/' . $row['thumbnail']) : '' }}"
                                data-description="{{ $row['description'] ?? '' }}"
                                onclick="selectProgramRow(this)">

                                {{-- No --}}
                                <td class="py-3 px-3 text-center font-bold text-slate-600">{{ $row['no'] }}</td>

                                {{-- Status (Waktu Jam Tayang) --}}
                                <td class="py-3 px-3 font-semibold text-slate-700 whitespace-nowrap">{{ $row['waktu'] }}</td>

                                {{-- Mata Acara + Kategori subtitle --}}
                                <td class="py-3 px-3">
                                    <div class="leading-tight">
                                        <p class="font-extrabold text-slate-900 text-xs sm:text-sm row-title">{{ $row['acara'] }}</p>
                                        @if(!empty($row['kategori']))
                                            <p class="text-[11px] text-slate-400 row-category">{{ $row['kategori'] }}</p>
                                        @else
                                            <p class="text-[11px] text-slate-300 italic row-category">Belum diklasifikasi</p>
                                        @endif
                                    </div>
                                </td>

                                {{-- Channel --}}
                                <td class="py-3 px-3 text-xs font-semibold text-slate-700">{{ $row['channel'] }}</td>

                                {{-- Durasi --}}
                                <td class="py-3 px-3 text-xs text-slate-600 whitespace-nowrap">{{ $row['durasi'] }}</td>

                                {{-- Thumbnail --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    @if(!empty($row['thumbnail']))
                                        <img src="{{ asset('images/thumbnails/' . $row['thumbnail']) }}"
                                             alt="{{ $row['acara'] }}"
                                             class="w-12 h-7 rounded object-cover mx-auto shadow-2xs border border-slate-200/70 row-thumb-img">
                                    @else
                                        <div class="w-12 h-7 rounded bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
                                            <iconify-icon icon="lucide:image" width="14"></iconify-icon>
                                        </div>
                                    @endif
                                </td>

                                {{-- Aksi (Status Siaran) --}}
                                <td class="py-3 px-3 text-center whitespace-nowrap">
                                    @if($row['status_type'] === 'live')
                                        <span class="inline-block bg-[#d90429] text-white text-[10px] font-extrabold px-3 py-0.5 rounded-full uppercase tracking-wider shadow-2xs">
                                            LIVE
                                        </span>
                                    @elseif($row['status_type'] === 'relay')
                                        <span class="inline-block bg-[#1d4ed8] text-white text-[10px] font-extrabold px-3 py-0.5 rounded-full uppercase tracking-wider shadow-2xs">
                                            RELAY
                                        </span>
                                    @elseif($row['status_type'] === 'rerun')
                                        <span class="inline-block bg-[#eab308] text-white text-[10px] font-extrabold px-3 py-0.5 rounded-full uppercase tracking-wider shadow-2xs">
                                            RERUN
                                        </span>
                                    @else
                                        <span class="inline-block bg-[#16a34a] text-white text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-2xs">
                                            PLAYBACK
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        {{-- Baris Pesan Kosong Saat Hasil Pencarian Tidak Ditemukan --}}
                        <tr id="emptySearchRow" class="hidden">
                            <td colspan="7" class="py-12 px-4 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <iconify-icon icon="heroicons:magnifying-glass" width="36" class="text-slate-300 mb-2"></iconify-icon>
                                    <p class="font-bold text-slate-600 text-sm">Tidak ada acara yang cocok</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian lain atau pilih tab filter lain</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination Dots --}}
            <div class="pt-4 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-slate-400 inline-block"></span>
                <span class="w-2 h-2 rounded-full bg-slate-200 inline-block"></span>
                <span class="w-2 h-2 rounded-full bg-slate-200 inline-block"></span>
                <span class="w-2 h-2 rounded-full bg-slate-200 inline-block"></span>
            </div>
        </div>

        {{-- KOLOM KANAN: Form Edit Detail Acara Sesuai Mockup Gambar 1 --}}
        <div class="w-full lg:w-[380px] xl:w-[410px] bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6 flex-shrink-0">
            
            {{-- Header Form --}}
            <div class="flex items-center gap-2 pb-4 mb-4 border-b border-slate-100 text-[#0c2461]">
                <iconify-icon icon="heroicons:pencil-square" width="18"></iconify-icon>
                <h3 class="text-sm font-extrabold tracking-tight">Edit Detail Acara</h3>
            </div>

            <form id="editProgramForm"
                  method="POST"
                  action="{{ route('admin.programs.update-detail') }}"
                  enctype="multipart/form-data"
                  class="space-y-4">
                @csrf

                {{-- Hidden Inputs --}}
                <input type="hidden" name="remove_thumbnail" id="input_remove_thumbnail" value="0">

                {{-- Field 1: Edit Nama Acara --}}
                <div>
                    <label for="form_program_title" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Edit Nama Acara
                    </label>
                    <input type="text"
                           id="form_program_title"
                           name="program_title"
                           value="{{ $programs[0]['acara'] ?? '' }}"
                           required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 transition">
                </div>

                {{-- Field 2: Thumbnail Saat Ini --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Thumbnail Saat Ini
                    </label>
                    
                    {{-- Kotak Preview Gambar --}}
                    <div class="rounded-2xl overflow-hidden border border-slate-200 bg-sky-400 h-[170px] relative shadow-2xs">
                        <img id="form_thumbnail_preview"
                             src="{{ asset('images/thumbnails/' . ($programs[0]['thumbnail'] ?? 'habarbanua.jpg')) }}"
                             alt="Thumbnail Acara"
                             class="w-full h-full object-cover">
                    </div>

                    {{-- Tombol Ganti Gambar & Hapus Gambar --}}
                    <div class="flex items-center gap-2 mt-2.5">
                        {{-- Hidden file input --}}
                        <input type="file"
                               id="form_file_input"
                               name="thumbnail_image"
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               class="hidden"
                               onchange="handleThumbnailFileChange(this)">

                        <button type="button"
                                onclick="document.getElementById('form_file_input').click()"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-[#0066ff] hover:bg-blue-700 transition cursor-pointer shadow-xs">
                            Ganti Gambar
                        </button>

                        <button type="button"
                                onclick="handleRemoveThumbnail()"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-[#dc2626] hover:bg-red-700 transition cursor-pointer shadow-xs">
                            Hapus Gambar
                        </button>
                    </div>
                </div>

                {{-- Field 3: Deskripsi Acara --}}
                <div>
                    <label for="form_description" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Deskripsi Acara
                    </label>
                    <textarea id="form_description"
                              name="description"
                              rows="4"
                              placeholder="Tuliskan deskripsi lengkap acara..."
                              class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 leading-relaxed focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">{{ $programs[0]['description'] ?? '' }}</textarea>
                </div>

                {{-- Field 4: Kategori Acara --}}
                <div>
                    <label for="form_category" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Kategori Acara
                    </label>
                    <select id="form_category"
                            name="category"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-blue-600 transition cursor-pointer">
                        <option value="">-- Tanpa Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ ($programs[0]['kategori'] ?? '') === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol Simpan (Warna Hijau Sesuai Gambar 1) --}}
                <div class="pt-2 flex justify-end">
                    <button type="submit"
                            id="btnSaveProgram"
                            class="px-6 py-2.5 rounded-lg font-bold text-xs tracking-wider text-white bg-[#16a34a] hover:bg-[#15803d] shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                        <iconify-icon icon="heroicons:check" width="16"></iconify-icon>
                        <span>Simpan</span>
                    </button>
                </div>
            </form>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // State row aktif
    let currentActiveRow = document.querySelector('.program-row');

    // Fungsi saat baris tabel acara di-klik
    function selectProgramRow(rowElement) {
        // Hapus style active dari baris lama
        document.querySelectorAll('.program-row').forEach(row => {
            row.classList.remove('bg-blue-50/60', 'ring-2', 'ring-blue-500/30', 'font-semibold');
        });

        // Tambah style active ke baris yang di-klik
        rowElement.classList.add('bg-blue-50/60', 'ring-2', 'ring-blue-500/30', 'font-semibold');
        currentActiveRow = rowElement;

        // Ambil data atribut
        const title       = rowElement.getAttribute('data-title');
        const category    = rowElement.getAttribute('data-category');
        const time        = rowElement.getAttribute('data-time');
        const thumbUrl    = rowElement.getAttribute('data-thumb-url');
        const description = rowElement.getAttribute('data-description');

        // Isi form Edit Detail Acara di sebelah kanan
        document.getElementById('form_program_title').value = title;
        document.getElementById('form_description').value   = description || '';
        document.getElementById('input_remove_thumbnail').value = '0';

        // Set preview gambar
        const previewImg = document.getElementById('form_thumbnail_preview');
        if (thumbUrl && thumbUrl.length > 5) {
            previewImg.src = thumbUrl;
            previewImg.style.display = 'block';
        } else {
            previewImg.src = '{{ asset("images/thumbnails/habarbanua.jpg") }}';
        }

        // Set dropdown kategori
        const categorySelect = document.getElementById('form_category');
        let categoryFound = false;
        for (let i = 0; i < categorySelect.options.length; i++) {
            if (category && categorySelect.options[i].value.toLowerCase() === category.toLowerCase()) {
                categorySelect.selectedIndex = i;
                categoryFound = true;
                break;
            }
        }
        if (!categoryFound) {
            categorySelect.selectedIndex = 0; // Pilih option '-- Tanpa Kategori --'
        }

        // Catatan: Card biru di atas adalah acara yang sedang tayang sekarang (seperti di beranda).
        // Card biru BUKAN preview baris yang dipilih, sehingga tetap menampilkan acara yang sedang on-air.
    }

    // Handle upload file thumbnail baru
    function handleThumbnailFileChange(fileInput) {
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('form_thumbnail_preview').src = e.target.result;
                document.getElementById('input_remove_thumbnail').value = '0';
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    }

    // Handle hapus gambar thumbnail
    function handleRemoveThumbnail() {
        if (confirm('Apakah Anda yakin ingin menghapus gambar thumbnail acara ini?')) {
            document.getElementById('form_thumbnail_preview').src = '{{ asset("images/thumbnails/habarbanua.jpg") }}';
            document.getElementById('form_file_input').value = '';
            document.getElementById('input_remove_thumbnail').value = '1';
        }
    }

    let currentAdminFilterTab = 'Semua';

    // Filter tab (Semua, Nasional, Kalsel, Talkshow, Religi, Dokumenter)
    function applyProgramFilter(tab) {
        currentAdminFilterTab = tab;

        // Update tab style
        document.querySelectorAll('.prog-tab-btn').forEach(btn => {
            btn.classList.remove('bg-[#0066ff]', 'text-white', 'shadow-xs');
            btn.classList.add('text-slate-600');
        });
        const activeBtn = document.querySelector(`[data-tab="${tab}"]`);
        if (activeBtn) {
            activeBtn.classList.add('bg-[#0066ff]', 'text-white', 'shadow-xs');
            activeBtn.classList.remove('text-slate-600');
        }

        // Terapkan filter gabungan (tab + search)
        filterAdminPrograms();
    }

    // Filter pencarian real-time + tab kategori pada tabel acara admin
    function filterAdminPrograms() {
        const searchInput = document.getElementById('adminProgramSearch');
        const clearBtn = document.getElementById('clearAdminSearchBtn');
        const query = (searchInput ? searchInput.value : '').toLowerCase().trim();

        // Tampilkan/sembunyikan tombol clear (X)
        if (clearBtn) {
            if (query.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }

        const rows = document.querySelectorAll('#adminProgramsTable tr.program-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const channel  = (row.getAttribute('data-channel') || '').toLowerCase();
            const category = (row.getAttribute('data-category') || '').toLowerCase();
            const title    = (row.getAttribute('data-title') || '').toLowerCase();
            const time     = (row.getAttribute('data-time') || '').toLowerCase();
            const rowText  = (row.textContent || '').toLowerCase();

            // 1. Cek kesesuaian Tab Kategori
            let matchTab = false;
            if (currentAdminFilterTab === 'Semua') {
                matchTab = true;
            } else if (currentAdminFilterTab === 'Nasional' && channel === 'nasional') {
                matchTab = true;
            } else if (currentAdminFilterTab === 'Kalsel' && channel === 'kalsel') {
                matchTab = true;
            } else if (category && (category === currentAdminFilterTab.toLowerCase() || category.includes(currentAdminFilterTab.toLowerCase()))) {
                matchTab = true;
            }

            // 2. Cek kesesuaian Kata Kunci Pencarian (Mata Acara, Kategori, Channel, Waktu)
            let matchSearch = true;
            if (query.length > 0) {
                matchSearch = title.includes(query) ||
                              category.includes(query) ||
                              channel.includes(query) ||
                              time.includes(query) ||
                              rowText.includes(query);
            }

            if (matchTab && matchSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Tampilkan/sembunyikan baris empty state
        const emptyRow = document.getElementById('emptySearchRow');
        if (emptyRow) {
            if (visibleCount === 0) {
                emptyRow.classList.remove('hidden');
            } else {
                emptyRow.classList.add('hidden');
            }
        }
    }

    // Reset kolom pencarian
    function clearAdminProgramSearch() {
        const searchInput = document.getElementById('adminProgramSearch');
        if (searchInput) {
            searchInput.value = '';
            searchInput.focus();
        }
        filterAdminPrograms();
    }

    // AJAX Form Submit untuk update detail acara secara instan dan mulus
    document.getElementById('editProgramForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const submitBtn = document.getElementById('btnSaveProgram');
        const originalText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<iconify-icon icon="line-md:loading-loop" width="16"></iconify-icon> <span>Menyimpan...</span>';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(res => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;

            if (res.success) {
                // Tampilkan notifikasi toast/alert
                alert(res.message);

                // Update data di baris tabel yang aktif
                if (currentActiveRow) {
                    const newCat = res.data.category || form.category.value;
                    const newDesc = res.data.description || form.description.value;
                    const newTitle = res.data.program_title || form.program_title.value;

                    currentActiveRow.setAttribute('data-category', newCat);
                    currentActiveRow.setAttribute('data-description', newDesc);
                    currentActiveRow.setAttribute('data-title', newTitle);

                    const titleEl = currentActiveRow.querySelector('.row-title');
                    const catEl = currentActiveRow.querySelector('.row-category');
                    if (titleEl) titleEl.innerText = newTitle;
                    if (catEl) {
                        catEl.innerText = newCat || 'Belum diklasifikasi';
                        if (!newCat) {
                            catEl.className = 'text-[11px] text-slate-300 italic row-category';
                        } else {
                            catEl.className = 'text-[11px] text-slate-400 row-category';
                        }
                    }

                    if (res.data.thumbnail_url) {
                        currentActiveRow.setAttribute('data-thumb-url', res.data.thumbnail_url);
                        const thumbEl = currentActiveRow.querySelector('.row-thumb-img');
                        if (thumbEl) thumbEl.src = res.data.thumbnail_url;
                        
                    }

                    // Jika yang diperbarui adalah acara yang sedang tayang sekarang (On Air), sinkronkan card biru atas
                    const onAirTitle = @json($currentOnAir['acara'] ?? 'HABAR BANUA');
                    if (newTitle === onAirTitle || (currentActiveRow && currentActiveRow.getAttribute('data-title') === onAirTitle)) {
                        const onAirTitleEl = document.getElementById('onair-banner-title');
                        const onAirCatEl   = document.getElementById('onair-banner-category');
                        const onAirDescEl  = document.getElementById('onair-banner-desc');
                        const onAirImgEl   = document.getElementById('onair-banner-img');

                        if (onAirTitleEl) onAirTitleEl.innerText = newTitle;
                        if (onAirCatEl)   onAirCatEl.innerText   = newCat;
                        if (onAirDescEl)  onAirDescEl.innerText  = newDesc;
                        if (onAirImgEl && res.data.thumbnail_url) onAirImgEl.src = res.data.thumbnail_url;
                    }
                }
            } else {
                alert('Terjadi kesalahan saat menyimpan data.');
            }
        })
        .catch(err => {
            console.error(err);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            // Fallback: submit biasa jika fetch gagal
            form.submit();
        });
    });

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
