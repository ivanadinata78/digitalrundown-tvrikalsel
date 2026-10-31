<nav x-data="{ open: false }" class="bg-white border-b border-slate-200/80 sticky top-0 z-40 shadow-xs">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo TVRI -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check() ? route('admin.dashboard') : route('home') }}" class="flex items-center gap-2 hover:opacity-90 transition">
                        <img src="{{ asset('images/logo/tvri_kalsel_lgo.png') }}" alt="TVRI Kalimantan Selatan" class="h-9 sm:h-10 w-auto object-contain">
                    </a>
                </div>

                <!-- Navigation Links Tengah (Sesuai Desain Mockup) -->
                <div class="hidden sm:flex sm:items-center sm:gap-6">
                    {{-- Beranda --}}
                    <a href="{{ route('admin.dashboard') }}"
                       class="relative py-5 flex items-center gap-1.5 text-sm font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 font-extrabold' : 'text-slate-600 hover:text-blue-600' }}">
                        <iconify-icon icon="heroicons:home-solid" width="17"></iconify-icon>
                        <span>Beranda</span>
                        @if(request()->routeIs('admin.dashboard'))
                            <span class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-blue-600 rounded-full"></span>
                        @endif
                    </a>

                    {{-- Data Acara --}}
                    <a href="{{ route('admin.dashboard') }}"
                       class="relative py-5 text-sm font-bold text-slate-600 hover:text-blue-600 transition-all">
                        <span>Data Acara</span>
                    </a>

                    {{-- Kategori --}}
                    <a href="{{ route('admin.categories.index') }}"
                       class="relative py-5 text-sm font-bold transition-all {{ request()->routeIs('admin.categories.*') ? 'text-blue-600 font-extrabold' : 'text-slate-600 hover:text-blue-600' }}">
                        <span>Kategori</span>
                        @if(request()->routeIs('admin.categories.*'))
                            <span class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-blue-600 rounded-full"></span>
                        @endif
                    </a>

                    {{-- Kelola Pengguna (Hanya Super Administrator) --}}
                    @if(auth()->check() && auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}"
                           class="relative py-5 text-sm font-bold transition-all {{ request()->routeIs('admin.users.*') ? 'text-blue-600 font-extrabold' : 'text-slate-600 hover:text-blue-600' }}">
                            <span>Kelola Pengguna</span>
                            @if(request()->routeIs('admin.users.*'))
                                <span class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-blue-600 rounded-full"></span>
                            @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- Profile Kanan: Menarik & Modern -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 relative" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false" @close.stop="userMenuOpen = false">
                <button @click="userMenuOpen = !userMenuOpen"
                        class="group inline-flex items-center gap-3 py-1.5 px-3 rounded-2xl hover:bg-slate-100/80 transition-all cursor-pointer text-left border border-transparent hover:border-slate-200/80">
                    
                    {{-- Avatar Trigger --}}
                    <div class="relative">
                        @if(Auth::user()?->avatar_url)
                            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-xs">
                        @else
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center font-extrabold text-sm shadow-xs border-2 border-white">
                                {{ strtoupper(substr(Auth::user()?->name ?? 'A', 0, 1)) }}
                            </div>
                        @endif
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                    </div>

                    {{-- Nama & Role --}}
                    <div class="flex flex-col leading-tight">
                        <span class="font-extrabold text-xs sm:text-sm text-slate-800 group-hover:text-blue-700 transition">{{ Auth::user()?->name ?? 'Pengguna' }}</span>
                        <span class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">
                            {{ (Auth::user()?->role === 'admin') ? 'Super Admin' : 'Karyawan' }}
                        </span>
                    </div>

                    {{-- Chevron Icon --}}
                    <iconify-icon icon="heroicons:chevron-down" width="16" class="text-slate-400 transition-transform duration-200" :class="{ 'rotate-180 text-blue-600': userMenuOpen }"></iconify-icon>
                </button>

                {{-- POPUP DROPDOWN KARTU MENARIK --}}
                <div x-show="userMenuOpen"
                     x-cloak
                     style="display: none; position: absolute; top: calc(100% + 8px); right: 0; width: 288px; z-index: 100;"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                     class="absolute right-0 top-full mt-2 w-72 rounded-3xl bg-white shadow-2xl border border-slate-100 overflow-hidden z-50">
                    
                    {{-- Header Popup Menarik --}}
                    <div class="p-4 bg-gradient-to-br from-[#0f2e60] via-[#173a77] to-[#1e40af] text-white relative overflow-hidden">
                        {{-- Background Accent Glow --}}
                        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-blue-400/20 rounded-full blur-xl pointer-events-none"></div>

                        <div class="flex items-center gap-3 relative z-10">
                            @if(Auth::user()?->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-12 h-12 rounded-2xl object-cover border-2 border-white/30 shadow-md">
                            @else
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-400 to-orange-500 text-white flex items-center justify-center font-black text-lg shadow-md border-2 border-white/30">
                                    {{ strtoupper(substr(Auth::user()?->name ?? 'A', 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h4 class="font-black text-sm text-white truncate leading-tight">{{ Auth::user()?->name }}</h4>
                                <span class="text-[11px] font-mono text-blue-200 block truncate"></span>
                                <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider {{ Auth::user()->isAdmin() ? 'bg-amber-400/25 text-amber-200 border border-amber-300/30' : 'bg-emerald-400/25 text-emerald-200 border border-emerald-300/30' }}">
                                    <iconify-icon icon="{{ Auth::user()->isAdmin() ? 'heroicons:shield-check-solid' : 'heroicons:user-solid' }}" width="11"></iconify-icon>
                                    <span>{{ Auth::user()->isAdmin() ? 'Super Administrator' : 'Karyawan / Operator' }}</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Menu Items --}}
                    <div class="p-2 space-y-1 text-slate-700">
                        {{-- Profil Saya --}}
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center justify-between px-3 py-2.5 rounded-2xl hover:bg-blue-50/80 text-xs font-bold text-slate-700 hover:text-blue-700 transition group">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                                    <iconify-icon icon="heroicons:user-circle-solid" width="18"></iconify-icon>
                                </div>
                                <div class="text-left">
                                    <span class="block font-extrabold text-slate-800 group-hover:text-blue-700">Profil Saya</span>
                                </div>
                            </div>
                            <iconify-icon icon="heroicons:chevron-right" width="14" class="text-slate-300 group-hover:text-blue-600 group-hover:translate-x-0.5 transition"></iconify-icon>
                        </a>

                        {{-- Halaman Publik --}}
                        <a href="{{ route('home') }}"
                           target="_blank"
                           class="flex items-center justify-between px-3 py-2.5 rounded-2xl hover:bg-slate-50 text-xs font-bold text-slate-700 hover:text-slate-900 transition group">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-slate-800 group-hover:text-white transition">
                                    <iconify-icon icon="heroicons:arrow-top-right-on-square" width="16"></iconify-icon>
                                </div>
                                <div class="text-left">
                                    <span class="block font-extrabold text-slate-800">Halaman Publik</span>
                                </div>
                            </div>
                            <iconify-icon icon="heroicons:chevron-right" width="14" class="text-slate-300 group-hover:translate-x-0.5 transition"></iconify-icon>
                        </a>

                        <div class="my-1 border-t border-slate-100"></div>

                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-2xl text-xs font-bold text-red-600 hover:bg-red-50 hover:text-red-700 transition cursor-pointer text-left group">
                                <div class="w-8 h-8 rounded-xl bg-red-50 text-red-500 flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition">
                                    <iconify-icon icon="heroicons:arrow-left-on-rectangle" width="16"></iconify-icon>
                                </div>
                                <div>
                                    <span class="block font-extrabold">Keluar (Log Out)</span>
                                </div>
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none transition cursor-pointer">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-100 bg-white px-3 py-2 space-y-1">
        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
            {{ __('Beranda') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
            {{ __('Kategori') }}
        </x-responsive-nav-link>

        @if(auth()->check() && auth()->user()->isAdmin())
            <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                {{ __('Kelola Pengguna') }}
            </x-responsive-nav-link>
        @endif

        <x-responsive-nav-link :href="route('rundown.index')" :active="request()->routeIs('rundown.index')">
            {{ __('Rundown Siaran') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('home')">
            {{ __('Halaman Publik') }}
        </x-responsive-nav-link>

        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-slate-100">
            @csrf
            <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    class="text-red-600 font-bold">
                {{ __('Keluar (Log Out)') }}
            </x-responsive-nav-link>
        </form>
    </div>
</nav>
