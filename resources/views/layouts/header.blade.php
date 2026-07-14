@php
    // Fetch notifications dynamically for the bell
    $readyLaundry = \App\Models\LaundryOrder::with('customer')
        ->where('status', 'selesai')
        ->latest()
        ->take(5)
        ->get();

    $unpaidLaundry = \App\Models\LaundryOrder::with('customer')
        ->whereIn('payment_status', ['belum_bayar', 'dp'])
        ->latest()
        ->take(5)
        ->get();

    $dueTenants = auth()->user()->isStaff()
        ? collect()
        : \App\Models\Tenant::with('room')
            ->where('status', 'aktif')
            ->get()
            ->filter(fn($t) => $t->days_remaining <= 7)
            ->take(5);

    $notifCount = $readyLaundry->count() + $unpaidLaundry->count() + $dueTenants->count();
@endphp

<header class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-6 z-10 shadow-sm transition-colors duration-200">
    <!-- Left Section: Sidebar Toggle and Breadcrumb -->
    <div class="flex items-center gap-4">
        <button type="button" @click="sidebarOpen = true" class="md:hidden p-2 rounded-md text-gray-500 hover:text-gray-900 focus:outline-none dark:text-gray-400 dark:hover:text-gray-100">
            <span class="sr-only">Open sidebar</span>
            <i data-lucide="menu" class="h-6 w-6"></i>
        </button>
        <div>
            <h1 class="text-lg font-semibold text-gray-900 dark:text-white capitalize">
                @yield('title', 'Sistem Terintegrasi')
            </h1>
        </div>
    </div>

    <!-- Right Section: Actions -->
    <div class="flex items-center gap-4">
        <!-- Dark Mode Toggle Button -->
        <button @click="darkMode = !darkMode; localStorage.setItem('color-theme', darkMode ? 'dark' : 'light'); if (darkMode) { document.documentElement.classList.add('dark') } else { document.documentElement.classList.remove('dark') }" 
                type="button" 
                class="p-2 rounded-lg text-gray-500 hover:text-gray-900 focus:outline-none dark:text-gray-400 dark:hover:text-gray-100 transition-colors"
                title="Ganti Tema">
            <span x-show="!darkMode"><i data-lucide="moon" class="h-5 w-5"></i></span>
            <span x-show="darkMode" style="display: none;"><i data-lucide="sun" class="h-5 w-5"></i></span>
        </button>

        <!-- Notification Bell Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" type="button" class="p-2 rounded-lg text-gray-500 hover:text-gray-900 focus:outline-none dark:text-gray-400 dark:hover:text-gray-100 transition-colors relative" title="Notifikasi">
                <i data-lucide="bell" class="h-5 w-5"></i>
                @if ($notifCount > 0)
                    <span class="absolute top-1.5 right-1.5 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
                @endif
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-75" 
                 x-transition:leave-start="transform opacity-100 scale-100" 
                 x-transition:leave-end="transform opacity-0 scale-95" 
                 class="origin-top-right absolute right-0 mt-2 w-80 rounded-xl shadow-lg py-1 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 focus:outline-none z-50 overflow-hidden" 
                 role="menu" 
                 style="display: none;">
                
                <div class="px-4 py-2.5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex justify-between items-center">
                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">Notifikasi</span>
                    <span class="text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 font-medium px-2 py-0.5 rounded-full">{{ $notifCount }} Baru</span>
                </div>

                <div class="max-h-72 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                    @if ($notifCount === 0)
                        <div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada notifikasi baru
                        </div>
                    @else
                        <!-- Tenant contract reminders -->
                        @foreach ($dueTenants as $tenant)
                            <a href="{{ route('tenants.index') }}" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                <div class="flex items-start gap-3">
                                    <div class="p-1 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 mt-0.5">
                                        <i data-lucide="clock" class="h-4 w-4"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-900 dark:text-white">Jatuh Tempo Kost</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">
                                            Kamar {{ $tenant->room->room_number }} ({{ $tenant->name }}) - 
                                            @if ($tenant->days_remaining < 0)
                                                Lewat {{ abs($tenant->days_remaining) }} hari!
                                            @elseif ($tenant->days_remaining == 0)
                                                Jatuh tempo hari ini!
                                            @else
                                                Sisa {{ $tenant->days_remaining }} hari kontrak.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                        <!-- Ready laundry reminders -->
                        @foreach ($readyLaundry as $order)
                            <a href="{{ route('laundry.orders.index') }}" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                <div class="flex items-start gap-3">
                                    <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mt-0.5">
                                        <i data-lucide="check-circle" class="h-4 w-4"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-900 dark:text-white">Laundry Selesai</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">
                                            Order {{ $order->order_number }} ({{ $order->customer->name }}) siap diambil/diantar.
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                        <!-- Unpaid laundry reminders -->
                        @foreach ($unpaidLaundry as $order)
                            <a href="{{ route('laundry.orders.index') }}" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                <div class="flex items-start gap-3">
                                    <div class="p-1 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 mt-0.5">
                                        <i data-lucide="alert-circle" class="h-4 w-4"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-900 dark:text-white">Pembayaran Belum Lunas</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">
                                            Order {{ $order->order_number }} ({{ $order->customer->name }}) status {{ strtoupper($order->payment_status) }}.
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
                
                <div class="px-4 py-2 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 text-center">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Sistem Pemantauan Terintegrasi</span>
                </div>
            </div>
        </div>

        <div class="h-8 w-px bg-gray-200 dark:bg-gray-700"></div>

        <!-- User Profile Dropdown -->
        <div class="flex items-center gap-3">
            <span class="hidden sm:inline-block text-sm font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->name }}</span>
            <div class="h-9 w-9 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 font-semibold border border-blue-200 dark:border-blue-800">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </div>
</header>
