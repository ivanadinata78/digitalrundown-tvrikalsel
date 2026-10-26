@extends('layouts.app')

@section('title', 'Kelola Pengguna Internal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 mb-1">
                <iconify-icon icon="heroicons:shield-check-solid" width="16"></iconify-icon>
                <span>Panel Super Administrator</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Pengguna</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola akun internal karyawan & operator rundown TVRI Kalsel tanpa email.</p>
        </div>

        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-bold text-xs tracking-wider text-white bg-gradient-to-r from-blue-700 to-indigo-600 hover:from-blue-800 hover:to-indigo-700 shadow-lg shadow-blue-900/20 hover:shadow-blue-900/30 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
            <iconify-icon icon="heroicons:user-plus-solid" width="18"></iconify-icon>
            <span>TAMBAH PENGGUNA BARU</span>
        </a>
    </div>

    {{-- Kartu Statistik Ringkas --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Users --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Pengguna</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-800 mt-1 block">{{ $stats['total'] }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <iconify-icon icon="heroicons:users-solid" width="24"></iconify-icon>
            </div>
        </div>

        {{-- Admin --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Super Admin</span>
                <span class="text-2xl sm:text-3xl font-black text-indigo-600 mt-1 block">{{ $stats['admin'] }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <iconify-icon icon="heroicons:shield-check-solid" width="24"></iconify-icon>
            </div>
        </div>

        {{-- Karyawan / Operator --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Karyawan / Operator</span>
                <span class="text-2xl sm:text-3xl font-black text-blue-600 mt-1 block">{{ $stats['karyawan'] }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                <iconify-icon icon="heroicons:briefcase-solid" width="24"></iconify-icon>
            </div>
        </div>

        {{-- Nonaktif --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Akun Dinonaktifkan</span>
                <span class="text-2xl sm:text-3xl font-black {{ $stats['non_aktif'] > 0 ? 'text-rose-600' : 'text-slate-400' }} mt-1 block">
                    {{ $stats['non_aktif'] }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl {{ $stats['non_aktif'] > 0 ? 'bg-rose-50 text-rose-600' : 'bg-slate-50 text-slate-400' }} flex items-center justify-center">
                <iconify-icon icon="heroicons:user-minus-solid" width="24"></iconify-icon>
            </div>
        </div>
    </div>

    {{-- Filter & Pencarian Bar --}}
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row items-center gap-3">
            {{-- Search Input --}}
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <iconify-icon icon="heroicons:magnifying-glass-solid" width="18"></iconify-icon>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari berdasarkan nama atau username..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-3 focus:ring-blue-600/15 transition-all">
            </div>

            {{-- Filter Role --}}
            <div class="w-full md:w-48">
                <select name="role"
                        onchange="this.form.submit()"
                        class="w-full py-2.5 px-3 bg-slate-50/70 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-blue-600 transition-all cursor-pointer">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="karyawan" {{ request('role') === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                </select>
            </div>

            {{-- Filter Status --}}
            <div class="w-full md:w-48">
                <select name="status"
                        onchange="this.form.submit()"
                        class="w-full py-2.5 px-3 bg-slate-50/70 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-blue-600 transition-all cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit"
                        class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold tracking-wide transition shadow-xs flex items-center justify-center gap-1.5 w-full md:w-auto">
                    <iconify-icon icon="heroicons:funnel-solid" width="16"></iconify-icon>
                    <span>Filter</span>
                </button>
                @if(request('search') || request('role') || request('status') !== null && request('status') !== '')
                    <a href="{{ route('admin.users.index') }}"
                       class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1 w-full md:w-auto"
                       title="Reset Filter">
                        <iconify-icon icon="heroicons:arrow-path" width="16"></iconify-icon>
                        <span class="md:hidden">Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel Pengguna --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 sm:px-6">Nama Pengguna</th>
                        <th class="py-3.5 px-4">Username Login</th>
                        <th class="py-3.5 px-4">Role Akses</th>
                        <th class="py-3.5 px-4 text-center">Status Akun</th>
                        <th class="py-3.5 px-4">Terdaftar Sejak</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-blue-50/30 transition-colors {{ !$user->is_active ? 'bg-slate-50/50 opacity-80' : '' }}">
                            {{-- Nama & Avatar --}}
                            <td class="py-3.5 px-4 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl {{ $user->isAdmin() ? 'bg-gradient-to-tr from-blue-700 to-indigo-600 text-white' : 'bg-slate-100 text-slate-700 border border-slate-200' }} flex items-center justify-center text-xs font-extrabold shrink-0 shadow-xs">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-extrabold text-slate-900 truncate flex items-center gap-1.5">
                                            <span>{{ $user->name }}</span>
                                            @if($user->id === auth()->id())
                                                <span class="bg-blue-100 text-blue-700 text-[9px] font-black uppercase px-2 py-0.5 rounded-full">
                                                    Anda
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-400">ID User: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Username --}}
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200/80 font-mono text-xs font-bold text-slate-800">
                                    <iconify-icon icon="heroicons:at-symbol" width="13" class="text-slate-400"></iconify-icon>
                                    <span>{{ $user->username }}</span>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 border border-indigo-200 text-indigo-700">
                                        <iconify-icon icon="heroicons:shield-check-solid" width="14"></iconify-icon>
                                        <span>Super Admin</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-sky-50 border border-sky-200 text-sky-700">
                                        <iconify-icon icon="heroicons:user-solid" width="14"></iconify-icon>
                                        <span>Karyawan</span>
                                    </span>
                                @endif
                            </td>

                            {{-- Status Aktif/Nonaktif --}}
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($user->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 border border-emerald-200 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-rose-50 border border-rose-200 text-rose-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 inline-block"></span>
                                        <span>Nonaktif</span>
                                    </span>
                                @endif
                            </td>

                            {{-- Tanggal Dibuat --}}
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-500 text-xs">
                                {{ $user->created_at ? $user->created_at->isoFormat('D MMM Y, HH:mm') : '-' }}
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3.5 px-4 sm:px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    {{-- Edit User / Ganti Password --}}
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="px-2.5 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs flex items-center gap-1 transition"
                                       title="Edit Data / Reset Password">
                                        <iconify-icon icon="heroicons:pencil-square" width="16"></iconify-icon>
                                        <span>Edit</span>
                                    </a>

                                    {{-- Tombol Toggle Status (Nonaktifkan/Aktifkan) --}}
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun {{ $user->username }}?')"
                                                    class="px-2.5 py-1.5 rounded-lg {{ $user->is_active ? 'bg-amber-50 hover:bg-amber-100 text-amber-700' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700' }} font-bold text-xs flex items-center gap-1 transition cursor-pointer"
                                                    title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                                <iconify-icon icon="{{ $user->is_active ? 'heroicons:no-symbol' : 'heroicons:check' }}" width="15"></iconify-icon>
                                                <span>{{ $user->is_active ? 'Suspend' : 'Aktifkan' }}</span>
                                            </button>
                                        </form>

                                        {{-- Tombol Hapus --}}
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus akun {{ $user->username }} secara permanen?')"
                                                    class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                                    title="Hapus Akun Pengguna">
                                                <iconify-icon icon="heroicons:trash" width="17"></iconify-icon>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                    <iconify-icon icon="heroicons:user-group" width="28"></iconify-icon>
                                </div>
                                <p class="text-sm font-bold text-slate-600">Tidak ada pengguna ditemukan.</p>
                                <p class="text-xs text-slate-400 mt-1">Coba ubah kata kunci pencarian atau bersihkan filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Info Card Box: Alur Kerja Tanpa Email --}}
    <div class="rounded-2xl p-5 bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white shadow-md flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-cyan-300 shrink-0">
                <iconify-icon icon="heroicons:information-circle-solid" width="24"></iconify-icon>
            </div>
            <div>
                <h4 class="font-extrabold text-sm sm:text-base text-white">Alur Akun Internal TVRI Kalimantan Selatan</h4>
                <p class="text-xs text-slate-300 mt-0.5 leading-relaxed">
                    Pengguna tidak memerlukan email untuk mendaftar atau login. Cukup bagikan <strong>Username</strong> dan <strong>Password</strong> yang dibuat oleh Super Admin ke karyawan bersangkutan. Jika karyawan lupa kata sandi, Super Admin dapat langsung meresetnya melalui tombol <strong>Edit</strong>.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
