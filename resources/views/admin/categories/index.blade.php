@extends('layouts.app')

@section('title', 'Kategori Acara')

@section('content')
<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6"
     x-data="{
        search: '',
        modalSearch: '',
        showCreateModal: false,
        showEditModal: false,
        showDeleteModal: false,
        showProgramsModal: false,
        showProgramDetailModal: false,
        selectedProgramDetail: {
            acara: '',
            waktu: '',
            durasi: '',
            kategori: '',
            description: '',
            status: '',
            status_type: ''
        },
        selectedCategory: {
            name: '',
            count: 0,
            programs: []
        },
        createColor: '#2563eb',
        editData: {
            id: null,
            name: '',
            color_hex: '#2563eb',
            update_url: ''
        },
        deleteData: {
            id: null,
            name: '',
            programs_count: 0,
            destroy_url: ''
        },
        openCreateModal() {
            this.createColor = '#2563eb';
            this.showCreateModal = true;
        },
        openEditModal(data) {
            this.editData = { ...data };
            this.showEditModal = true;
        },
        openDeleteModal(data) {
            this.deleteData = { ...data };
            this.showDeleteModal = true;
        },
        openProgramsModal(category) {
            this.modalSearch = '';
            this.selectedCategory = {
                name: category.name || '',
                count: category.count || 0,
                programs: category.programs || []
            };
            this.showProgramsModal = true;
        },
        openProgramDetail(prog) {
            this.selectedProgramDetail = {
                acara: prog.acara || '',
                waktu: prog.waktu || '-',
                durasi: prog.durasi || '-',
                kategori: prog.kategori || this.selectedCategory.name || '',
                description: prog.description || 'Menghadirkan tayangan berkualitas dan informasi terkini dari TVRI Kalimantan Selatan untuk masyarakat Banua.',
                status: prog.status || 'REGULER',
                status_type: prog.status_type || 'default'
            };
            this.showProgramDetailModal = true;
        },
        get filteredModalPrograms() {
            if (!this.selectedCategory.programs) return [];
            if (!this.modalSearch.trim()) return this.selectedCategory.programs;
            return this.selectedCategory.programs.filter(p =>
                (p.acara || '').toLowerCase().includes(this.modalSearch.toLowerCase().trim())
            );
        },
        matchesSearch(name) {
            if (!this.search.trim()) return true;
            return name.toLowerCase().includes(this.search.toLowerCase().trim());
        }
     }">

    {{-- Dot Matrix Dekorasi Sudut Sesuai Mockup Gambar 1 --}}
    <div class="absolute top-2 right-4 grid grid-cols-6 gap-2.5 opacity-25 pointer-events-none hidden md:grid">
        @for($i = 0; $i < 24; $i++)
            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
        @endfor
    </div>
    <div class="absolute bottom-2 left-4 grid grid-cols-6 gap-2.5 opacity-25 pointer-events-none hidden md:grid">
        @for($i = 0; $i < 18; $i++)
            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
        @endfor
    </div>

    {{-- Baris Atas: Judul & Input Pencarian di Sisi Kiri (Sesuai Lebar Kolom Tabel) --}}
    <div class="flex flex-col lg:flex-row items-start gap-6 lg:gap-8 relative z-10 mb-3.5">
        <div class="w-full lg:flex-1 space-y-3.5">
            {{-- Judul Halaman --}}
            <div class="flex items-center gap-2.5">
                <iconify-icon icon="heroicons:squares-2x2-solid" width="26" class="text-[#0052cc]"></iconify-icon>
                <h1 class="text-2xl sm:text-3xl font-black text-[#002b66] tracking-tight">Kategori Acara</h1>
            </div>

            {{-- Input Pencarian Real-Time --}}
            <div class="relative w-full">
                <iconify-icon icon="heroicons:magnifying-glass" width="18" class="text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></iconify-icon>
                <input type="text"
                       x-model="search"
                       placeholder=""
                       class="w-full pl-10 pr-8 py-2.5 bg-white border border-slate-200/90 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 shadow-2xs focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all">
                <button type="button"
                        x-show="search.length > 0"
                        @click="search = ''"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                    <iconify-icon icon="heroicons:x-mark" width="16"></iconify-icon>
                </button>
            </div>
        </div>

        {{-- Spacer Kolom Kanan agar bilah pencarian tetap sejajar dengan batas kanan tabel --}}
        <div class="hidden lg:block w-[380px] shrink-0"></div>
    </div>

    {{-- Baris Utama: Card Tabel Kategori (Kiri) & Statistik + Tombol Tambah (Kanan) --}}
    {{-- Posisi container statistik dan tombol disetarakan/sejajar sempurna dengan tabel kategori di sampingnya --}}
    <div class="flex flex-col lg:flex-row items-start gap-6 lg:gap-8 relative z-10">
        
        {{-- ============================================================== --}}
        {{-- SISI KIRI: Card Tabel Kategori --}}
        {{-- ============================================================== --}}
        <div class="w-full lg:flex-1">
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-[#f1f5f9] text-slate-700 text-xs font-bold border-b border-slate-200/80">
                                <th class="py-3 px-4 w-14 text-center">No</th>
                                <th class="py-3 px-5">Nama Kategori</th>
                                <th class="py-3 px-4 text-center">Penggunaan</th>
                                <th class="py-3 px-5 text-center w-36">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                            @forelse($categories as $index => $cat)
                                <tr class="hover:bg-blue-50/20 transition-colors"
                                    x-show="matchesSearch('{{ addslashes(strtolower($cat->name)) }}')"
                                    x-transition.opacity>
                                    {{-- Nomor --}}
                                    <td class="py-3.5 px-4 text-center text-xs font-semibold text-slate-600">
                                        {{ $index + 1 }}
                                    </td>

                                    {{-- Nama Kategori (Dapat diklik untuk membuka pop up) --}}
                                    <td class="py-3.5 px-5">
                                        <button type="button"
                                                @click="openProgramsModal(@js([
                                                    'name' => $cat->name,
                                                    'count' => $cat->programs_count,
                                                    'programs' => $cat->rundown_programs ?? []
                                                ]))"
                                                class="font-bold text-sm text-slate-800 hover:text-[#0052cc] transition cursor-pointer text-left">
                                            {{ $cat->name }}
                                        </button>
                                    </td>

                                    {{-- Penggunaan (Badge Pill Biru Tua Sesuai Gambar 1) --}}
                                    <td class="py-3.5 px-4 text-center">
                                        <button type="button"
                                                @click="openProgramsModal(@js([
                                                    'name' => $cat->name,
                                                    'count' => $cat->programs_count,
                                                    'programs' => $cat->rundown_programs ?? []
                                                ]))"
                                                class="px-4 py-1 rounded-full bg-[#0052cc] hover:bg-[#0041a8] text-white font-black text-xs shadow-2xs hover:shadow-xs transition-all cursor-pointer inline-flex items-center justify-center">
                                            {{ $cat->programs_count }} Acara
                                        </button>
                                    </td>

                                    {{-- Aksi (Edit Biru & Hapus Merah Sesuai Gambar 1) --}}
                                    <td class="py-3.5 px-5 text-center">
                                        <div class="inline-flex items-center justify-center gap-3.5">
                                             {{-- Tombol Edit --}}
                                            <button type="button"
                                                    @click="openEditModal({
                                                        id: {{ $cat->id }},
                                                        name: '{{ addslashes($cat->name) }}',
                                                        color_hex: '{{ $cat->color_hex ?? '#2563eb' }}',
                                                        update_url: '{{ route('admin.categories.update', $cat) }}'
                                                    })"
                                                    class="inline-flex items-center gap-1 text-xs font-bold text-[#0284c7] hover:text-[#0369a1] hover:underline transition cursor-pointer"
                                                    title="Ubah Kategori">
                                                <iconify-icon icon="heroicons:pencil-square" width="16"></iconify-icon>
                                                <span>Edit</span>
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            <button type="button"
                                                    @click="openDeleteModal({
                                                        id: {{ $cat->id }},
                                                        name: '{{ addslashes($cat->name) }}',
                                                        programs_count: {{ $cat->programs_count ?? 0 }},
                                                        destroy_url: '{{ route('admin.categories.destroy', $cat) }}'
                                                    })"
                                                    class="inline-flex items-center gap-1 text-xs font-bold text-red-500 hover:text-red-700 hover:underline transition cursor-pointer"
                                                    title="Hapus Kategori">
                                                <iconify-icon icon="heroicons:trash" width="16"></iconify-icon>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-slate-400 text-xs">
                                        Belum ada kategori yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- SISI KANAN: Kartu Statistik & Tombol Tambah Kategori (Sejajar dengan Tabel Kategori) --}}
        {{-- ============================================================== --}}
        <div class="w-full lg:w-[380px] shrink-0 space-y-4">
            {{-- 2 Kartu Statistik Berdampingan --}}
            <div class="grid grid-cols-2 gap-3.5">
                {{-- Card Total Kategori --}}
                <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-bold text-slate-800">Total Kategori</span>
                    <div class="flex items-center gap-2.5 mt-3">
                        <div class="w-11 h-11 rounded-xl bg-[#0052cc] text-white flex items-center justify-center shrink-0 shadow-xs">
                            <iconify-icon icon="heroicons:tag-solid" width="22"></iconify-icon>
                        </div>
                        <div class="text-base sm:text-lg font-black text-[#0052cc] tracking-tight leading-tight">
                            {{ $categories->count() }} Kategori
                        </div>
                    </div>
                </div>

                {{-- Card Tayangan Terpetakan --}}
                <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-bold text-slate-800">Tayangan Terpetakan</span>
                    <div class="flex items-center gap-2.5 mt-3">
                        <div class="w-11 h-11 rounded-xl bg-[#0052cc] text-white flex items-center justify-center shrink-0 shadow-xs">
                            <iconify-icon icon="heroicons:computer-desktop-solid" width="22"></iconify-icon>
                        </div>
                        <div class="leading-tight">
                            <div class="text-base sm:text-lg font-black text-[#0052cc] tracking-tight">
                                {{ $mappedCount }}/{{ $totalPrograms }}
                            </div>
                            <span class="block text-[10px] font-bold text-[#0052cc] leading-none mt-0.5">
                                Acara Terpetakan
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Tambah Kategori (Hijau Cerah Sesuai Gambar 1) --}}
            <button type="button"
                    @click="openCreateModal()"
                    class="w-full py-3.5 px-6 rounded-2xl font-black text-sm text-white bg-[#009e2a] hover:bg-[#0d8e2f] shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer">
                <iconify-icon icon="heroicons:plus-circle-solid" width="22"></iconify-icon>
                <span>Tambah Kategori</span>
            </button>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- MODAL DAFTAR ACARA PER KATEGORI (SESUAI GAMBAR 2) --}}
    {{-- ============================================================== --}}
    <div x-show="showProgramsModal"
         x-cloak
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 flex flex-col gap-4"
             @click.away="showProgramsModal = false"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            {{-- Header Modal: Nama Kategori, Pill Jumlah, & Kolom Cari --}}
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 min-w-0">
                    <h3 class="text-2xl sm:text-3xl font-black text-[#002b66] tracking-tight truncate" x-text="selectedCategory.name"></h3>
                    <span class="px-3.5 py-1 rounded-full bg-[#0052cc] text-white text-xs font-black shrink-0"
                          x-text="selectedCategory.count + ' Acara'"></span>
                </div>
                
                {{-- Kolom Cari di Dalam Pop Up --}}
                <div class="relative w-36 sm:w-44 shrink-0">
                    <iconify-icon icon="heroicons:magnifying-glass" width="14" class="text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></iconify-icon>
                    <input type="text"
                           x-model="modalSearch"
                           placeholder=""
                           class="w-full pl-8 pr-3 py-1.5 rounded-full border border-slate-200 text-xs font-medium text-slate-800 bg-white shadow-2xs focus:outline-none focus:border-blue-500">
                </div>
            </div>

            {{-- Body Modal: Container Abu-Abu dengan List Acara Berbentuk Pill Putih --}}
            <div class="bg-[#f1f3f5] rounded-[1.8rem] p-4 sm:p-5 min-h-[320px] max-h-[440px] overflow-y-auto space-y-2.5 border border-slate-200/60">
                <template x-for="(prog, idx) in filteredModalPrograms" :key="idx">
                    <div class="w-full px-5 py-3 rounded-full bg-white font-black text-sm text-[#002b66] border border-slate-200/80 shadow-2xs transition hover:scale-[1.01] hover:border-blue-300 hover:text-[#0052cc] cursor-pointer flex items-center justify-between group"
                         @click="openProgramDetail(prog)"
                         title="Klik untuk melihat deskripsi dan jam tayang">
                        <span x-text="(idx + 1) + '. ' + prog.acara"></span>
                        <div class="flex items-center gap-1.5 text-xs text-slate-400 group-hover:text-[#0052cc]">
                            <span class="text-[11px] font-semibold" x-text="prog.waktu && prog.waktu !== '-' ? prog.waktu : ''"></span>
                            <iconify-icon icon="heroicons:chevron-right" width="16"></iconify-icon>
                        </div>
                    </div>
                </template>

                <div x-show="filteredModalPrograms.length === 0" class="py-24 text-center text-slate-400 text-xs font-bold">
                    Tidak ada acara yang tersedia dalam kategori ini.
                </div>
            </div>

            {{-- Footer Modal: Tombol TUTUP (Outline Merah, Hover: Filled Merah + Teks Putih) --}}
            <div class="flex justify-end pt-1">
                <button type="button"
                        @click="showProgramsModal = false"
                        class="px-8 py-2 rounded-full border-2 border-[#ff0000] text-[#ff0000] hover:bg-[#ff0000] hover:text-white font-black text-xs tracking-wider transition-all duration-200 uppercase cursor-pointer shadow-2xs hover:shadow-sm">
                    TUTUP
                </button>
            </div>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- MODAL DETAIL ACARA (DESKRIPSI & JAM TAYANG) DARI LIST KATEGORI --}}
    {{-- ============================================================== --}}
    <div x-show="showProgramDetailModal"
         x-cloak
         style="display: none;"
         class="fixed inset-0 z-60 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 flex flex-col gap-4"
             @click.away="showProgramDetailModal = false"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            {{-- Header Detail Acara --}}
            <div class="flex items-start justify-between pb-2 border-b border-slate-100 gap-2">
                <div>
                    <span class="text-[10px] uppercase font-bold text-blue-600 tracking-wider block" x-text="selectedProgramDetail.kategori"></span>
                    <h3 class="text-xl sm:text-2xl font-black text-[#002b66] tracking-tight leading-snug" x-text="selectedProgramDetail.acara"></h3>
                </div>
                <button type="button" @click="showProgramDetailModal = false" class="text-slate-400 hover:text-slate-600 transition p-1 cursor-pointer">
                    <iconify-icon icon="heroicons:x-mark" width="20"></iconify-icon>
                </button>
            </div>

            {{-- Jam Tayang & Durasi --}}
            <div class="grid grid-cols-2 gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-100/70 text-[#0052cc] flex items-center justify-center shrink-0">
                        <iconify-icon icon="lucide:clock" width="18"></iconify-icon>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Jam Tayang</span>
                        <span class="text-xs sm:text-sm font-black text-slate-800 block" x-text="selectedProgramDetail.waktu + (selectedProgramDetail.waktu !== '-' ? ' WITA' : '')"></span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100/70 text-indigo-600 flex items-center justify-center shrink-0">
                        <iconify-icon icon="lucide:timer" width="18"></iconify-icon>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Durasi</span>
                        <span class="text-xs sm:text-sm font-black text-slate-800 block" x-text="selectedProgramDetail.durasi"></span>
                    </div>
                </div>
            </div>

            {{-- Deskripsi Acara --}}
            <div class="space-y-1.5">
                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <iconify-icon icon="heroicons:document-text-solid" width="16" class="text-[#0052cc]"></iconify-icon>
                    <span>Deskripsi Acara</span>
                </div>
                <div class="bg-slate-50/70 rounded-xl p-3.5 border border-slate-200/70">
                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-medium" x-text="selectedProgramDetail.description"></p>
                </div>
            </div>

            {{-- Footer Tombol Tutup --}}
            <div class="flex justify-end pt-2">
                <button type="button"
                        @click="showProgramDetailModal = false"
                        class="px-8 py-2 rounded-full border-2 border-[#ff0000] text-[#ff0000] hover:bg-[#ff0000] hover:text-white font-black text-xs tracking-wider transition-all duration-200 uppercase cursor-pointer shadow-2xs hover:shadow-sm">
                    TUTUP
                </button>
            </div>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- MODAL TAMBAH KATEGORI --}}
    {{-- ============================================================== --}}
    <div x-show="showCreateModal"
         x-cloak
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100"
             @click.away="showCreateModal = false"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <iconify-icon icon="heroicons:plus-circle-solid" width="20"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-sm sm:text-base text-slate-900">Tambah Kategori Baru</h3>
                        <p class="text-[11px] text-slate-400">Masukkan label klasifikasi siaran baru</p>
                    </div>
                </div>
                <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 transition p-1">
                    <iconify-icon icon="heroicons:x-mark" width="20"></iconify-icon>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="create_name" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Nama Kategori</label>
                    <input id="create_name"
                           type="text"
                           name="name"
                           required
                           autofocus
                           placeholder="Contoh: Berita, Talkshow, Budaya..."
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-3 focus:ring-blue-600/15 transition">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button"
                            @click="showCreateModal = false"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-blue-700 to-indigo-600 hover:from-blue-800 hover:to-indigo-700 shadow-md shadow-blue-900/20 transition cursor-pointer">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- MODAL EDIT KATEGORI --}}
    {{-- ============================================================== --}}
    <div x-show="showEditModal"
         x-cloak
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100"
             @click.away="showEditModal = false"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <iconify-icon icon="heroicons:pencil-square-solid" width="18"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-sm sm:text-base text-slate-900">Ubah Kategori</h3>
                        <p class="text-[11px] text-slate-400">Perbarui nama kategori siaran</p>
                    </div>
                </div>
                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition p-1">
                    <iconify-icon icon="heroicons:x-mark" width="20"></iconify-icon>
                </button>
            </div>

            <form method="POST" :action="editData.update_url" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit_name" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Nama Kategori</label>
                    <input id="edit_name"
                           type="text"
                           name="name"
                           x-model="editData.name"
                           required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-3 focus:ring-blue-600/15 transition">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button"
                            @click="showEditModal = false"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-blue-700 to-indigo-600 hover:from-blue-800 hover:to-indigo-700 shadow-md shadow-blue-900/20 transition cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- MODAL KONFIRMASI HAPUS --}}
    {{-- ============================================================== --}}
    <div x-show="showDeleteModal"
         x-cloak
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 text-center"
             @click.away="showDeleteModal = false"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-3">
                <iconify-icon icon="heroicons:exclamation-triangle-solid" width="26"></iconify-icon>
            </div>

            <h3 class="font-extrabold text-base text-slate-900">Hapus Kategori?</h3>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                Kategori <span class="font-bold text-slate-800" x-text="'&quot;' + deleteData.name + '&quot;'"></span> akan dihapus dari sistem.
            </p>

            <template x-if="deleteData.programs_count > 0">
                <div class="mt-3 p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-[11px] text-left flex items-start gap-2">
                    <iconify-icon icon="heroicons:information-circle-solid" width="16" class="text-amber-600 shrink-0 mt-0.5"></iconify-icon>
                    <span>Ada <strong x-text="deleteData.programs_count"></strong> tayangan siaran yang menggunakan kategori ini. Label kategorinya akan dikosongkan.</span>
                </div>
            </template>

            <div class="mt-5 flex items-center justify-center gap-2">
                <button type="button"
                        @click="showDeleteModal = false"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                    Batal
                </button>
                <form method="POST" :action="deleteData.destroy_url">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-sm shadow-rose-900/20 transition cursor-pointer">
                        Ya, Hapus Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
