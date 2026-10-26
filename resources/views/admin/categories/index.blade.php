@extends('layouts.app')

@section('title', 'Kategori Acara')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Container Card Utama --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-8 min-h-[500px]" x-data="{ showModal: false }">
        
        {{-- Header: Judul Kategori & Tombol Tambah --}}
        <div class="flex items-center justify-between pb-6 mb-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="text-[#0c2461] flex items-center justify-center">
                    <iconify-icon icon="heroicons:squares-2x2-solid" width="24"></iconify-icon>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-[#0c2461] tracking-tight">Kategori</h1>
            </div>

            <button type="button"
                    @click="showModal = true"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg font-bold text-xs text-white bg-[#16a34a] hover:bg-[#15803d] shadow-sm transition-all cursor-pointer">
                <span>+ Tambah Kategori</span>
            </button>
        </div>

        {{-- Tabel Kategori Sesuai Gambar Mockup --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-600 border-b border-slate-100">
                        <th class="py-3 px-4 w-16 text-center font-bold">No</th>
                        <th class="py-3 px-4 font-bold">Kategori</th>
                        <th class="py-3 px-4 w-28 text-right font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $index => $cat)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-700">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $cat->name }}</td>
                            <td class="py-3.5 px-4 text-right">
                                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="inline" onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 transition p-1" title="Hapus Kategori">
                                        <iconify-icon icon="heroicons:trash" width="16"></iconify-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-slate-400 font-medium">Belum ada kategori acara.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modal Tambah Kategori --}}
        <div x-show="showModal"
             x-cloak
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100" @click.away="showModal = false">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <h3 class="font-extrabold text-base text-slate-900">Tambah Kategori Baru</h3>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                        <iconify-icon icon="heroicons:x-mark" width="20"></iconify-icon>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Nama Kategori</label>
                        <input id="name"
                               type="text"
                               name="name"
                               required
                               placeholder="Contoh: Berita, Talkshow, Hiburan..."
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 transition">
                    </div>

                    <div class="pt-3 flex items-center justify-end gap-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-lg text-xs font-bold text-white bg-[#16a34a] hover:bg-[#15803d] shadow-sm transition">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
