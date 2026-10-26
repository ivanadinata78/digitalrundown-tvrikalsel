@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition">
            <iconify-icon icon="heroicons:arrow-left-20-solid" width="16"></iconify-icon>
            <span>Kembali ke Daftar Pengguna</span>
        </a>
    </div>

    {{-- Form Container --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        
        {{-- Header Form --}}
        <div class="flex items-center gap-3.5 pb-6 mb-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0">
                <iconify-icon icon="heroicons:user-plus-solid" width="24"></iconify-icon>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Tambah Pengguna Baru</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Buat akun internal untuk staf atau operator stasiun TVRI Kalsel.</p>
            </div>
        </div>

        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs">
                <div class="font-bold mb-1.5 flex items-center gap-1.5">
                    <iconify-icon icon="heroicons:exclamation-circle-solid" width="18" class="text-red-600 shrink-0"></iconify-icon>
                    <span>Terdapat kesalahan pengisian data:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-red-600 pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf

            {{-- Nama Lengkap --}}
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 flex items-center gap-1.5">
                    <iconify-icon icon="heroicons:identification-solid" width="16" class="text-blue-600"></iconify-icon>
                    <span>Nama Lengkap Pegawai / Operator</span>
                    <span class="text-rose-500">*</span>
                </label>
                <input id="name"
                       type="text"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       autofocus
                       placeholder="Contoh: Muhammad Budi Santoso"
                       class="w-full px-4 py-3 bg-slate-50 border {{ $errors->has('name') ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200' }} rounded-xl text-sm font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/15 transition-all">
            </div>

            {{-- Username Login --}}
            <div>
                <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 flex items-center gap-1.5">
                    <iconify-icon icon="heroicons:at-symbol" width="16" class="text-blue-600"></iconify-icon>
                    <span>Username Login</span>
                    <span class="text-rose-500">*</span>
                </label>
                <input id="username"
                       type="text"
                       name="username"
                       value="{{ old('username') }}"
                       required
                       placeholder="Contoh: budi_tvri / operator1"
                       class="w-full px-4 py-3 bg-slate-50 border {{ $errors->has('username') ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200' }} rounded-xl text-sm font-mono font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/15 transition-all">
                <p class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-1">
                    <iconify-icon icon="heroicons:information-circle" width="14"></iconify-icon>
                    <span>Gunakan huruf kecil tanpa spasi. Boleh memakai angka, strip (-), dan garis bawah (_).</span>
                </p>
            </div>

            {{-- Role Pengguna --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2 flex items-center gap-1.5">
                    <iconify-icon icon="heroicons:shield-check-solid" width="16" class="text-blue-600"></iconify-icon>
                    <span>Peran / Hak Akses (Role)</span>
                    <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Role Karyawan --}}
                    <label class="relative flex items-start p-4 rounded-2xl border-2 border-slate-200 hover:border-blue-500 bg-slate-50/50 hover:bg-blue-50/20 cursor-pointer transition-all has-checked:border-blue-600 has-checked:bg-blue-50/30">
                        <input type="radio"
                               name="role"
                               value="karyawan"
                               {{ old('role', 'karyawan') === 'karyawan' ? 'checked' : '' }}
                               class="mt-1 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        <div class="ml-3">
                            <span class="block text-xs font-extrabold text-slate-900 uppercase tracking-wide">Karyawan / Operator</span>
                            <span class="block text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                                Dapat melihat rundown dan memperbarui status penayangan siaran.
                            </span>
                        </div>
                    </label>

                    {{-- Role Super Admin --}}
                    <label class="relative flex items-start p-4 rounded-2xl border-2 border-slate-200 hover:border-indigo-500 bg-slate-50/50 hover:bg-indigo-50/20 cursor-pointer transition-all has-checked:border-indigo-600 has-checked:bg-indigo-50/30">
                        <input type="radio"
                               name="role"
                               value="admin"
                               {{ old('role') === 'admin' ? 'checked' : '' }}
                               class="mt-1 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        <div class="ml-3">
                            <span class="block text-xs font-extrabold text-indigo-900 uppercase tracking-wide">Super Administrator</span>
                            <span class="block text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                                Akses penuh: manajemen rundown, kategori, saluran, dan manajemen pengguna.
                            </span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Password Awal --}}
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 flex items-center gap-1.5">
                    <iconify-icon icon="heroicons:lock-closed-solid" width="16" class="text-blue-600"></iconify-icon>
                    <span>Password Awal Akun</span>
                    <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input id="password"
                           type="password"
                           name="password"
                           required
                           placeholder="Minimal 6 karakter..."
                           class="w-full pl-4 pr-11 py-3 bg-slate-50 border {{ $errors->has('password') ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200' }} rounded-xl text-sm font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/15 transition-all">
                    
                    <button type="button"
                            id="togglePasswordBtn"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-blue-600 transition cursor-pointer"
                            title="Lihat / Sembunyikan Password">
                        <iconify-icon id="togglePasswordIcon" icon="heroicons:eye-solid" width="18"></iconify-icon>
                    </button>
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5">Password ini akan diberikan kepada karyawan untuk login pertama kali.</p>
            </div>

            {{-- Status Akun --}}
            <div class="pt-2">
                <label class="inline-flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200/80 cursor-pointer select-none">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           {{ old('is_active', '1') ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    <div>
                        <span class="text-xs font-extrabold text-slate-800 block">Status Akun Aktif</span>
                        <span class="text-[11px] text-slate-500 block">Akun dapat langsung digunakan untuk masuk ke portal.</span>
                    </div>
                </label>
            </div>

            {{-- Submit Buttons --}}
            <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}"
                   class="px-5 py-3 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-100 transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-6 py-3 rounded-xl font-bold text-xs tracking-wider text-white bg-gradient-to-r from-blue-700 to-indigo-600 hover:from-blue-800 hover:to-indigo-700 shadow-md shadow-blue-900/20 hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2 cursor-pointer">
                    <iconify-icon icon="heroicons:check-solid" width="18"></iconify-icon>
                    <span>SIMPAN PENGGUNA</span>
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.setAttribute('icon', isPassword ? 'heroicons:eye-slash-solid' : 'heroicons:eye-solid');
            });
        }
    });
</script>
@endpush
