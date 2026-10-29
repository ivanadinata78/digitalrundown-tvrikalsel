@extends('layouts.app')

@section('title', 'Edit Profile — TVRI Kalimantan Selatan')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Breadcrumb / Kembali --}}
    <div>
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition">
            <iconify-icon icon="heroicons:arrow-left-20-solid" width="16"></iconify-icon>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    {{-- Header: Blue bar indicator + Edit Profile --}}
    <div class="flex items-center gap-2.5">
        <span class="w-2.5 h-6 bg-blue-600 rounded-full inline-block"></span>
        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Edit Profile</h1>
    </div>

    {{-- Alert Status Berhasil --}}
    @if (session('status'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2.5 shadow-xs">
            <iconify-icon icon="heroicons:check-circle-solid" width="20" class="text-emerald-600 shrink-0"></iconify-icon>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs">
            <div class="font-bold mb-1 flex items-center gap-1.5 text-red-800">
                <iconify-icon icon="heroicons:exclamation-circle-solid" width="16" class="text-red-600 shrink-0"></iconify-icon>
                <span>Terjadi kesalahan:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-red-600 pl-1 text-[11px]">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main Container Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
        
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <input type="hidden" name="remove_avatar" id="input_remove_avatar" value="0">
            <input type="file"
                   id="avatarFileInput"
                   name="avatar"
                   accept="image/jpeg,image/png,image/jpg,image/webp"
                   class="hidden"
                   onchange="handleAvatarFileSelect(this)">

            {{-- Layout 2 Kolom Selalu Berdampingan di Desktop / Tablet --}}
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8 lg:gap-12">
                
                {{-- ============================================================== --}}
                {{-- KOLOM KIRI: KOTAK FOTO PROFIL (UKURAN RAPI & TETAP)           --}}
                {{-- ============================================================== --}}
                <div class="flex flex-col items-center shrink-0" style="width: 210px; max-width: 210px;">
                    
                    {{-- Frame Foto Profil dengan Batasan Ukuran Pasti --}}
                    <div class="relative rounded-3xl bg-slate-50 border-2 border-slate-200/90 shadow-sm overflow-hidden flex items-center justify-center group"
                         style="width: 200px; height: 200px; max-width: 200px; max-height: 200px; min-width: 200px; min-height: 200px;">
                        
                        {{-- Tombol Kamera Kiri Atas Sesuai Desain --}}
                        <button type="button"
                                onclick="document.getElementById('avatarFileInput').click()"
                                class="absolute top-2.5 left-2.5 w-8 h-8 rounded-lg bg-white/95 hover:bg-white text-slate-700 hover:text-blue-600 shadow-md border border-slate-200 flex items-center justify-center transition cursor-pointer z-20"
                                title="Klik untuk upload foto baru">
                            <iconify-icon icon="heroicons:camera-solid" width="16"></iconify-icon>
                        </button>

                        {{-- Preview Gambar Avatar (Terkunci di 200x200 dengan Object-Fit Cover) --}}
                        <img id="avatarPreviewImg"
                             src="{{ $user->avatar_url ?? '' }}"
                             alt="{{ $user->name }}"
                             class="{{ $user->avatar_url ? 'block' : 'hidden' }}"
                             style="width: 200px; height: 200px; max-width: 200px; max-height: 200px; object-fit: cover;">
                        
                        {{-- Fallback jika belum upload foto --}}
                        <div id="avatarFallback"
                             class="{{ $user->avatar_url ? 'hidden' : 'flex' }} flex-col items-center justify-center text-center w-full h-full bg-slate-100 p-4">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-[#0f2e60] to-[#2563eb] text-white flex items-center justify-center font-black text-2xl shadow-sm mb-1.5">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="text-[10px] font-bold text-slate-400">Belum Ada Foto</span>
                        </div>
                    </div>

                    {{-- Dua Kotak Dashed Sesuai Referensi (Ganti Foto & Hapus Foto) --}}
                    <div class="grid grid-cols-2 gap-2 mt-3" style="width: 200px;">
                        
                        {{-- Kotak Dashed 1: Ganti Foto Profil --}}
                        <button type="button"
                                onclick="document.getElementById('avatarFileInput').click()"
                                class="py-2.5 px-2 rounded-xl border-2 border-dashed border-blue-300 hover:border-blue-600 bg-blue-50/50 hover:bg-blue-100/50 text-blue-600 font-bold text-[10px] uppercase tracking-wider flex flex-col items-center justify-center gap-1 transition cursor-pointer shadow-2xs">
                            <iconify-icon icon="heroicons:arrow-up-tray" width="16"></iconify-icon>
                            <span>Ganti Foto</span>
                        </button>

                        {{-- Kotak Dashed 2: Hapus Foto Profil --}}
                        <button type="button"
                                id="btnRemoveAvatar"
                                onclick="handleRemoveAvatar()"
                                class="py-2.5 px-2 rounded-xl border-2 border-dashed {{ $user->avatar ? 'border-red-300 hover:border-red-500 bg-red-50/40 text-red-600 cursor-pointer' : 'border-slate-200 bg-slate-50 text-slate-300 cursor-not-allowed' }} font-bold text-[10px] uppercase tracking-wider flex flex-col items-center justify-center gap-1 transition shadow-2xs"
                                {{ $user->avatar ? '' : 'disabled' }}>
                            <iconify-icon icon="heroicons:trash" width="16"></iconify-icon>
                            <span>Hapus Foto</span>
                        </button>
                    </div>

                    <p class="text-[10px] text-slate-400 mt-2 text-center block">
                        Maksimal 2 MB (JPG, PNG, WebP)
                    </p>
                </div>

                {{-- ============================================================== --}}
                {{-- KOLOM KANAN: FORM FIELD INPUT & TOMBOL SAVE / CANCEL           --}}
                {{-- ============================================================== --}}
                <div class="flex-1 w-full space-y-4 pt-1">
                    
                    {{-- Row 1: Name --}}
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-4">
                        <label for="name" class="w-24 shrink-0 text-xs font-bold text-slate-500">
                            Name:
                        </label>
                        <input id="name"
                               type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               required
                               placeholder="User name"
                               style="padding: 10px 16px;"
                               class="flex-1 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                    </div>

                    {{-- Row 2: Username --}}
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-4">
                        <label for="username" class="w-24 shrink-0 text-xs font-bold text-slate-500">
                            Username:
                        </label>
                        <input id="username"
                               type="text"
                               name="username"
                               value="{{ old('username', $user->username) }}"
                               required
                               placeholder="username"
                               style="padding: 10px 16px;"
                               class="flex-1 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                    </div>

                    {{-- Row 3: Role (Read-Only) --}}
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-4">
                        <span class="w-24 shrink-0 text-xs font-bold text-slate-500">
                            Role:
                        </span>
                        <div class="flex-1 px-4 py-2 bg-slate-50/70 border border-slate-200/80 rounded-xl flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-800">
                                <iconify-icon icon="{{ $user->isAdmin() ? 'heroicons:shield-check-solid' : 'heroicons:briefcase-solid' }}" width="15" class="{{ $user->isAdmin() ? 'text-amber-600' : 'text-blue-600' }}"></iconify-icon>
                                <span>{{ $user->isAdmin() ? 'Super Administrator' : 'Karyawan / Operator' }}</span>
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium">(Diatur oleh Admin)</span>
                        </div>
                    </div>

                    {{-- Row 4: Status Akun (Read-Only) --}}
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-4">
                        <span class="w-24 shrink-0 text-xs font-bold text-slate-500">
                            Status:
                        </span>
                        <div class="flex-1 px-4 py-2 bg-slate-50/70 border border-slate-200/80 rounded-xl flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-bold text-emerald-700">Akun Aktif</span>
                        </div>
                    </div>

                    {{-- Bottom Action Buttons: SAVE & CANCEL Sesuai Desain Asli --}}
                    <div class="pt-6 flex items-center gap-3">
                        {{-- Tombol SAVE Berbentuk Persegi Panjang Rapi --}}
                        <button type="submit"
                                class="font-black text-xs uppercase tracking-wider text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] rounded-xl shadow-md shadow-blue-600/20 hover:shadow-blue-600/30 transition cursor-pointer"
                                style="padding: 12px 36px; min-width: 120px; display: inline-flex; align-items: center; justify-content: center;">
                            SAVE
                        </button>

                        {{-- Tombol CANCEL Berbentuk Persegi Panjang Rapi --}}
                        <a href="{{ route('admin.dashboard') }}"
                           class="font-black text-xs uppercase tracking-wider text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition cursor-pointer text-center"
                           style="padding: 12px 36px; min-width: 120px; display: inline-flex; align-items: center; justify-content: center;">
                            CANCEL
                        </a>
                    </div>

                </div>

            </div>
        </form>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function handleAvatarFileSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.getElementById('avatarPreviewImg');
                const fallback = document.getElementById('avatarFallback');
                const btnRemove = document.getElementById('btnRemoveAvatar');
                const inputRemove = document.getElementById('input_remove_avatar');

                img.src = e.target.result;
                img.style.display = 'block';
                img.style.width = '200px';
                img.style.height = '200px';
                img.style.objectFit = 'cover';
                img.classList.remove('hidden');
                img.classList.add('block');
                
                if (fallback) {
                    fallback.classList.remove('flex');
                    fallback.classList.add('hidden');
                }
                
                if (btnRemove) {
                    btnRemove.classList.remove('border-slate-200', 'bg-slate-50', 'text-slate-300', 'cursor-not-allowed');
                    btnRemove.classList.add('border-red-300', 'hover:border-red-500', 'bg-red-50/40', 'text-red-600', 'cursor-pointer');
                    btnRemove.removeAttribute('disabled');
                }
                if (inputRemove) {
                    inputRemove.value = '0';
                }
            };

            reader.readAsDataURL(file);
        }
    }

    function handleRemoveAvatar() {
        const img = document.getElementById('avatarPreviewImg');
        const fallback = document.getElementById('avatarFallback');
        const btnRemove = document.getElementById('btnRemoveAvatar');
        const inputRemove = document.getElementById('input_remove_avatar');
        const fileInput = document.getElementById('avatarFileInput');

        if (fileInput) fileInput.value = '';
        if (img) {
            img.src = '';
            img.style.display = 'none';
            img.classList.remove('block');
            img.classList.add('hidden');
        }
        if (fallback) {
            fallback.classList.remove('hidden');
            fallback.classList.add('flex');
        }
        if (btnRemove) {
            btnRemove.classList.remove('border-red-300', 'hover:border-red-500', 'bg-red-50/40', 'text-red-600', 'cursor-pointer');
            btnRemove.classList.add('border-slate-200', 'bg-slate-50', 'text-slate-300', 'cursor-not-allowed');
            btnRemove.setAttribute('disabled', 'disabled');
        }
        if (inputRemove) {
            inputRemove.value = '1';
        }
    }
</script>
@endpush
