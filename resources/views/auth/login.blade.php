<x-guest-layout>
    @section('title', 'Login')

    <div class="w-full" style="max-width: 410px; width: 100%; margin: 0 auto;">
        {{-- Card Container Kotak --}}
        <div class="bg-white rounded-3xl shadow-[0_15px_35px_-5px_rgba(15,46,96,0.12),0_0_1px_1px_rgba(15,46,96,0.06)] border border-slate-100 p-6 sm:p-8 relative">
            
            {{-- Header Card --}}
            <div class="flex flex-col items-center text-center mb-6">
                {{-- Icon Badge --}}
                <div class="w-13 h-13 rounded-2xl bg-gradient-to-tr from-[#0f2e60] to-[#2563eb] text-white flex items-center justify-center shadow-md shadow-blue-900/20 mb-3" style="width: 52px; height: 52px;">
                    <iconify-icon icon="heroicons:user-solid" width="26"></iconify-icon>
                </div>
                <h1 class="text-2xl font-extrabold text-[#0f2e60] tracking-tight">LOGIN</h1>
            </div>

            {{-- Flash Alert Status / Errors --}}
            @if (session('status'))
                <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                    <iconify-icon icon="heroicons:check-circle-solid" width="16" class="text-emerald-600 shrink-0"></iconify-icon>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold flex items-center gap-2">
                    <iconify-icon icon="heroicons:exclamation-triangle-solid" width="16" class="text-red-600 shrink-0"></iconify-icon>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium">
                    <div class="flex items-center gap-1.5 font-bold mb-1">
                        <iconify-icon icon="heroicons:exclamation-circle-solid" width="16" class="text-red-600 shrink-0"></iconify-icon>
                        <span>Gagal Masuk</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px] text-red-600 pl-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Field Username --}}
                <div>
                    <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 flex items-center gap-1.5">
                        <iconify-icon icon="heroicons:identification-solid" width="15" class="text-blue-600"></iconify-icon>
                        <span>Username</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <iconify-icon icon="heroicons:user-solid" width="18"></iconify-icon>
                        </div>
                        <input id="username"
                               type="text"
                               name="username"
                               value="{{ old('username') }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="Masukkan username"
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border {{ (isset($errors) && $errors->has('username')) ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200' }} rounded-xl text-sm font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/15 hover:border-slate-300 transition-all shadow-xs">
                    </div>
                </div>

                {{-- Field Password --}}
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 flex items-center gap-1.5">
                        <iconify-icon icon="heroicons:lock-closed-solid" width="15" class="text-blue-600"></iconify-icon>
                        <span>Password</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <iconify-icon icon="heroicons:key-solid" width="18"></iconify-icon>
                        </div>
                        <input id="password"
                               type="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full pl-10 pr-11 py-3 bg-slate-50 border {{ (isset($errors) && $errors->has('password')) ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200' }} rounded-xl text-sm font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/15 hover:border-slate-300 transition-all shadow-xs">
                        
                        {{-- Toggle Password Button --}}
                        <button type="button"
                                id="togglePasswordBtn"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-blue-600 transition cursor-pointer"
                                title="Lihat / Sembunyikan Password">
                            <iconify-icon id="togglePasswordIcon" icon="heroicons:eye-solid" width="18"></iconify-icon>
                        </button>
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input id="remember_me"
                               type="checkbox"
                               name="remember"
                               class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        <span class="text-xs font-semibold text-slate-600">Ingat Saya</span>
                    </label>
                </div>

                {{-- Submit Button (Warna Biru Prominen) --}}
                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3.5 px-6 rounded-xl font-bold text-sm tracking-wider text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] shadow-lg shadow-blue-600/30 transition-all flex items-center justify-center cursor-pointer"
                            style="background-color: #2563eb; color: #ffffff;">
                        <span>MASUK</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

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
</x-guest-layout>
