<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8 bg-slate-50 dark:bg-slate-950 relative overflow-hidden" 
         x-data="{ showPassword: false }">
        
        <!-- Ambient Background Mesh Glow -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-500/10 dark:bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-600/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="w-full max-w-md z-10 space-y-6">
            
            <!-- Logo & Business Branding -->
            <div class="text-center space-y-2">
                <a href="/" class="inline-flex items-center justify-center gap-3 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/25 ring-1 ring-white/20 group-hover:scale-105 transition-transform duration-200">
                        <i data-lucide="layers" class="w-6 h-6 text-white"></i>
                    </div>
                </a>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                    {{ \App\Models\Setting::get('business_name', 'Lestari Laundry & Kost') }}
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Masuk ke Portal Operasional
                </p>
            </div>

            <!-- Login Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80 shadow-xl shadow-slate-200/50 dark:shadow-black/40 rounded-2xl p-6 sm:p-8 space-y-5">
                
                <!-- Session Status Banner -->
                @if (session('status'))
                    <div class="p-3.5 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30 text-blue-700 dark:text-blue-300 text-xs flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4 shrink-0"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Validation Error Alert -->
                @if ($errors->any())
                    <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 text-rose-700 dark:text-rose-300 text-xs space-y-1">
                        <div class="flex items-center gap-2 font-semibold text-rose-600 dark:text-rose-400">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            <span>Gagal Masuk</span>
                        </div>
                        <ul class="list-disc list-inside pl-1 text-slate-600 dark:text-slate-300 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Username Field -->
                    <div class="space-y-1.5">
                        <label for="username" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                            Username / ID Petugas
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>
                            <input id="username" 
                                   name="username" 
                                   type="text" 
                                   value="{{ old('username') }}"
                                   required 
                                   autofocus 
                                   autocomplete="username" 
                                   placeholder="Masukkan username Anda"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 transition-all duration-150" />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Kata Sandi
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                    Lupa Kata Sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="key-round" class="w-4 h-4"></i>
                            </div>
                            <input id="password" 
                                   name="password" 
                                   :type="showPassword ? 'text' : 'password'" 
                                   required 
                                   autocomplete="current-password" 
                                   placeholder="••••••••"
                                   class="w-full pl-10 pr-11 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 transition-all duration-150" />
                            
                            <!-- Toggle Password Visibility Button -->
                            <button type="button" 
                                    @click="showPassword = !showPassword" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors focus:outline-none"
                                    title="Tampilkan / Sembunyikan Kata Sandi">
                                <template x-if="!showPassword">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </template>
                                <template x-if="showPassword">
                                    <i data-lucide="eye-off" class="w-4 h-4"></i>
                                </template>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="inline-flex items-center gap-2.5 cursor-pointer">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   name="remember" 
                                   class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-blue-600 focus:ring-blue-500/40 cursor-pointer">
                            <span class="text-xs text-slate-600 dark:text-slate-400 select-none">Ingat Sesi Login</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-600/20 flex items-center justify-center gap-2 transition-all duration-150">
                        <span>Masuk ke Sistem</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>

            </div>

            <!-- Footer & Security Badge -->
            <div class="text-center space-y-3">
                <div class="flex items-center justify-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                    <a href="/" class="hover:text-slate-800 dark:hover:text-white transition-colors flex items-center gap-1">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                        <span>Kembali ke Beranda</span>
                    </a>
                    <span>•</span>
                    <a href="{{ route('portal.index') }}" class="hover:text-slate-800 dark:hover:text-white transition-colors">
                        Portal Cek Status Order
                    </a>
                </div>

                <div class="flex items-center justify-center gap-1.5 text-[11px] text-slate-400 dark:text-slate-500">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-500"></i>
                    <span>Koneksi Aman Terenkripsi HTTPS</span>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
