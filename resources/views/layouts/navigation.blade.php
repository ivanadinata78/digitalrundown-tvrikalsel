<nav x-data="{ open: false }" class="bg-white border-b border-slate-200/80 sticky top-0 z-40 shadow-xs">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo TVRI -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ (auth()->check() && auth()->user()->isAdmin()) ? route('admin.dashboard') : route('rundown.index') }}" class="flex items-center gap-2 hover:opacity-90 transition">
                        <img src="{{ asset('images/logo/tvri_kalsel_lgo.png') }}" alt="TVRI Kalimantan Selatan" class="h-9 sm:h-10 w-auto object-contain">
                    </a>
                </div>

                <!-- Navigation Links Tengah (Sesuai Desain Mockup) -->
                <div class="hidden sm:flex sm:items-center sm:gap-6">
                    @if(auth()->check() && auth()->user()->isAdmin())
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

                        {{-- Kelola Pengguna --}}
                        <a href="{{ route('admin.users.index') }}"
                           class="relative py-5 text-sm font-bold transition-all {{ request()->routeIs('admin.users.*') ? 'text-blue-600 font-extrabold' : 'text-slate-600 hover:text-blue-600' }}">
                            <span>Kelola Pengguna</span>
                            @if(request()->routeIs('admin.users.*'))
                                <span class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-blue-600 rounded-full"></span>
                            @endif
                        </a>
                    @else
                        <a href="{{ route('rundown.index') }}"
                           class="relative py-5 text-sm font-bold text-blue-600 transition-all">
                            <span>Rundown Siaran</span>
                            <span class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-blue-600 rounded-full"></span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Profile Kanan (Sesuai Mockup: Foto Avatar + Nama + ADMIN) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 px-2 py-1 rounded-full hover:bg-slate-50 transition cursor-pointer text-left">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center font-bold text-sm shadow-xs border border-white">
                                {{ strtoupper(substr(Auth::user()?->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="flex flex-col leading-tight">
                                <span class="font-extrabold text-sm text-slate-900">{{ Auth::user()?->name ?? 'Administrator' }}</span>
                                <span class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">
                                    {{ (Auth::user()?->role === 'admin') ? 'ADMIN' : 'KARYAWAN' }}
                                </span>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-slate-100 bg-slate-50/50">
                            <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Username</div>
                            <div class="text-xs font-bold text-blue-600">@<span>{{ Auth::user()->username }}</span></div>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 text-xs">
                            <iconify-icon icon="heroicons:user-circle" width="16" class="text-slate-500"></iconify-icon>
                            <span>{{ __('Profil Saya') }}</span>
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('home')" target="_blank" class="flex items-center gap-2 text-xs text-slate-700">
                            <iconify-icon icon="heroicons:arrow-top-right-on-square" width="16" class="text-slate-500"></iconify-icon>
                            <span>{{ __('Halaman Publik') }}</span>
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center gap-2 text-xs text-red-600 hover:text-red-700 hover:bg-red-50">
                                <iconify-icon icon="heroicons:arrow-left-on-rectangle" width="16" class="text-red-500"></iconify-icon>
                                <span>{{ __('Keluar (Log Out)') }}</span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
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
        @if(auth()->check() && auth()->user()->isAdmin())
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                {{ __('Beranda') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                {{ __('Kategori') }}
            </x-responsive-nav-link>

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
