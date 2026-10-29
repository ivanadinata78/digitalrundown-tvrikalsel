@extends('layouts.app')

@section('title', 'Edit Pengguna — ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

    {{-- Kembali --}}
    <div>
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition">
            <iconify-icon icon="heroicons:arrow-left-20-solid" width="16"></iconify-icon>
            <span>Kembali ke Daftar Pengguna</span>
        </a>
    </div>

    {{-- Form Container --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
        
        {{-- Header Form --}}
        <div class="pb-5 mb-5 border-b border-slate-100">
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Edit Data Pengguna</h1>
        </div>

        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="mb-5 p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs">
                <div class="font-bold mb-1 flex items-center gap-1.5 text-red-800">
                    <iconify-icon icon="heroicons:exclamation-circle-solid" width="16" class="text-red-600 shrink-0"></iconify-icon>
                    <span>Terjadi kesalahan data:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-red-600 pl-1 text-[11px]">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Nama Lengkap --}}
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                <input id="name"
                       type="text"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       required
                       class="w-full px-4 py-2.5 bg-slate-50 border {{ $errors->has('name') ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200' }} rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-3 focus:ring-blue-600/15 transition-all">
            </div>

            {{-- Username Login --}}
            <div>
                <label for="username" class="block text-xs font-bold text-slate-700 mb-1.5">Username</label>
                <input id="username"
                       type="text"
                       name="username"
                       value="{{ old('username', $user->username) }}"
                       required
                       class="w-full px-4 py-2.5 bg-slate-50 border {{ $errors->has('username') ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200' }} rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-3 focus:ring-blue-600/15 transition-all">
            </div>

            {{-- Role Pengguna --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Peran (Hak Akses)</label>

                @if($user->id === auth()->id())
                    <div class="p-3 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs font-medium">
                        Akun Anda saat ini adalah <strong>Super Administrator</strong> (role tidak dapat diubah sendiri).
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        {{-- Role Karyawan --}}
                        <label class="relative flex items-start gap-3 p-3.5 rounded-2xl border-2 border-slate-200 hover:border-blue-500 bg-slate-50/50 cursor-pointer transition has-checked:border-blue-600 has-checked:bg-blue-50/30">
                            <input type="radio"
                                   name="role"
                                   value="karyawan"
                                   {{ old('role', $user->role) === 'karyawan' ? 'checked' : '' }}
                                   class="mt-0.5 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            <div>
                                <span class="block text-xs font-bold text-slate-900">Karyawan / Operator</span>
                                <span class="block text-[11px] text-slate-500 mt-0.5 leading-snug">Ubah thumbnail, kategori, & deskripsi acara</span>
                            </div>
                        </label>

                        {{-- Role Super Admin --}}
                        <label class="relative flex items-start gap-3 p-3.5 rounded-2xl border-2 border-slate-200 hover:border-blue-500 bg-slate-50/50 cursor-pointer transition has-checked:border-blue-600 has-checked:bg-blue-50/30">
                            <input type="radio"
                                   name="role"
                                   value="admin"
                                   {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}
                                   class="mt-0.5 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            <div>
                                <span class="block text-xs font-bold text-slate-900">Super Administrator</span>
                                <span class="block text-[11px] text-slate-500 mt-0.5 leading-snug">Akses penuh sistem & kelola akun pengguna</span>
                            </div>
                        </label>
                    </div>
                @endif
            </div>

            {{-- Reset Password Baru --}}
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Password Baru <span class="text-slate-400 font-normal">(Kosongkan jika tidak diubah)</span>
                </label>
                <div class="relative">
                    <input id="password"
                           type="password"
                           name="password"
                           placeholder="Masukkan password baru (minimal 6 karakter)..."
                           class="w-full pl-4 pr-11 py-2.5 bg-slate-50 border {{ $errors->has('password') ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200' }} rounded-xl text-sm font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-3 focus:ring-blue-600/15 transition-all">
                    
                    <button type="button"
                            id="togglePasswordBtn"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-blue-600 transition cursor-pointer"
                            title="Lihat / Sembunyikan Password">
                        <iconify-icon id="togglePasswordIcon" icon="heroicons:eye-solid" width="18"></iconify-icon>
                    </button>
                </div>
            </div>

            {{-- Status Akun --}}
            @if($user->id !== auth()->id())
                <div class="pt-1">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        <span class="text-xs font-bold text-slate-700">Status Akun Aktif</span>
                    </label>
                </div>
            @endif

            {{-- Submit Buttons --}}
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl font-bold text-xs tracking-wider text-white bg-blue-600 hover:bg-blue-700 transition-all cursor-pointer shadow-xs">
                    SIMPAN PERUBAHAN
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
