<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Pelacakan Pelanggan</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite compiled) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col transition-colors duration-200">

    <!-- Top Navigation -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 py-4 px-6 shadow-sm transition-colors duration-200">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <span class="text-lg font-bold text-gray-950 dark:text-white flex items-center gap-2">
                <i data-lucide="waves" class="h-5 w-5 text-blue-600"></i>
                Lestari Laundry & Kost
            </span>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-655 text-gray-800 dark:text-gray-200 px-3.5 py-2 rounded-xl transition-colors">
                <i data-lucide="lock" class="h-3.5 w-3.5"></i> Portal Staff / Admin
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-3xl w-full mx-auto px-6 py-12 space-y-8">
        
        <!-- Welcome Header -->
        <div class="text-center space-y-2">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Lacak Cucian & Kontrak Kost Anda</h1>
            <p class="text-sm text-gray-550 dark:text-gray-400">Masukkan Nomor Order Laundry atau Nama/Nomor HP Penyewa Kost untuk cek status real-time.</p>
        </div>

        <!-- Search Card -->
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm">
            <form method="GET" action="{{ route('portal.index') }}" class="flex gap-2">
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-450 dark:text-gray-550">
                        <i data-lucide="search" class="h-5 w-5"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           required 
                           placeholder="Masukkan ID Order (ORD-...) atau Nama/No HP Kost..."
                           class="block w-full pl-11 pr-4 py-3 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-750 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white">
                </div>
                <button type="submit" class="px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-colors shadow-sm shadow-blue-900/10">
                    Cari
                </button>
            </form>
        </div>

        <!-- Search Results -->
        @if($searched)
            @if($laundryOrder)
                <!-- Laundry Order Details -->
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 md:p-8 shadow-sm space-y-8">
                    <!-- Status Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-widest font-semibold">Resi Order Laundry</span>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $laundryOrder->order_number }}</h2>
                        </div>
                        <div class="text-left sm:text-right">
                            <span class="text-xs text-gray-500 block uppercase font-semibold tracking-wider">Status Pembayaran</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold mt-1 capitalize
                                {{ $laundryOrder->payment_status === 'lunas' 
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' 
                                    : ($laundryOrder->payment_status === 'dp' 
                                        ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' 
                                        : 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300') }}">
                                {{ $laundryOrder->payment_status === 'dp' ? 'DP (Uang Muka)' : $laundryOrder->payment_status }}
                            </span>
                        </div>
                    </div>

                    <!-- Visual Progress Stepper -->
                    <div class="relative py-4">
                        <!-- Connecting Line -->
                        <div class="absolute left-4 top-4 bottom-4 w-0.5 bg-gray-200 dark:bg-gray-700 sm:left-6 sm:right-6 sm:top-1/2 sm:bottom-auto sm:w-auto sm:h-0.5 sm:-translate-y-1/2 z-0"></div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 relative z-10">
                            <!-- Step 1 -->
                            <div class="flex sm:flex-col items-center gap-4 sm:gap-2 text-center">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300
                                    {{ in_array($laundryOrder->status, ['baru', 'proses', 'selesai', 'diambil_diantar']) 
                                        ? 'bg-blue-600 text-white ring-4 ring-blue-100 dark:ring-blue-900/30' 
                                        : 'bg-gray-200 text-gray-400 dark:bg-gray-700 dark:text-gray-500' }}">
                                    <i data-lucide="sparkles" class="h-4 w-4"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Diterima</h4>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Baru Masuk</p>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex sm:flex-col items-center gap-4 sm:gap-2 text-center">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300
                                    {{ in_array($laundryOrder->status, ['proses', 'selesai', 'diambil_diantar']) 
                                        ? 'bg-blue-600 text-white ring-4 ring-blue-100 dark:ring-blue-900/30' 
                                        : 'bg-gray-200 text-gray-400 dark:bg-gray-700 dark:text-gray-500' }}">
                                    <i data-lucide="loader-2" class="h-4 w-4 {{ $laundryOrder->status === 'proses' ? 'animate-spin' : '' }}"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold {{ in_array($laundryOrder->status, ['proses', 'selesai', 'diambil_diantar']) ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}">Diproses</h4>
                                    <p class="text-[10px] text-gray-550">Pencucian / Setrika</p>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex sm:flex-col items-center gap-4 sm:gap-2 text-center">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300
                                    {{ in_array($laundryOrder->status, ['selesai', 'diambil_diantar']) 
                                        ? 'bg-blue-600 text-white ring-4 ring-blue-100 dark:ring-blue-900/30' 
                                        : 'bg-gray-200 text-gray-400 dark:bg-gray-700 dark:text-gray-500' }}">
                                    <i data-lucide="check" class="h-4 w-4"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold {{ in_array($laundryOrder->status, ['selesai', 'diambil_diantar']) ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}">Selesai</h4>
                                    <p class="text-[10px] text-gray-550">Siap Diambil</p>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="flex sm:flex-col items-center gap-4 sm:gap-2 text-center">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300
                                    {{ $laundryOrder->status === 'diambil_diantar' 
                                        ? 'bg-green-600 text-white ring-4 ring-green-100 dark:ring-green-900/30' 
                                        : 'bg-gray-200 text-gray-400 dark:bg-gray-700 dark:text-gray-500' }}">
                                    <i data-lucide="package-check" class="h-4 w-4"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold {{ $laundryOrder->status === 'diambil_diantar' ? 'text-green-600 dark:text-green-400 font-bold' : 'text-gray-400 dark:text-gray-500' }}">Diserahkan</h4>
                                    <p class="text-[10px] text-gray-550">Sudah di Tangan Anda</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Breakdown info -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl p-5 border border-gray-100 dark:border-gray-800 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-xs text-gray-500">Nama Pelanggan</span>
                            <span class="block font-semibold mt-0.5 text-gray-900 dark:text-white">{{ $laundryOrder->customer->name }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Layanan</span>
                            <span class="block font-semibold mt-0.5 text-gray-900 dark:text-white">{{ $laundryOrder->service->name }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Berat / Satuan</span>
                            <span class="block font-semibold mt-0.5 text-gray-900 dark:text-white">{{ $laundryOrder->weight }} {{ $laundryOrder->weight_unit ?: 'kg' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Estimasi Selesai</span>
                            <span class="block font-semibold mt-0.5 text-gray-900 dark:text-white">
                                {{ $laundryOrder->estimation_date ? $laundryOrder->estimation_date->format('d M Y, H:i') : '-' }}
                            </span>
                        </div>
                    </div>

                    <!-- Billing & Surcharges -->
                    <div class="border-t border-gray-100 dark:border-gray-700 pt-5 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Harga Layanan</span>
                            <span class="text-gray-900 dark:text-white">Rp {{ number_format($laundryOrder->selectedServicePrice * $laundryOrder->weight, 0, ',', '.') }}</span>
                        </div>
                        @if($laundryOrder->is_express)
                            <div class="flex justify-between text-sm text-amber-600 dark:text-amber-400">
                                <span>Tambahan Express</span>
                                <span>+ Rp {{ number_format($laundryOrder->fee_express ?: 5000, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($laundryOrder->delivery_type !== 'none')
                            <div class="flex justify-between text-sm text-indigo-600 dark:text-indigo-400 capitalize">
                                <span>Biaya Antar Jemput ({{ $laundryOrder->delivery_type }})</span>
                                <span>+ Rp {{ number_format(($laundryOrder->fee_pickup ?: 0) + ($laundryOrder->fee_delivery ?: 0), 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-base font-bold border-t border-gray-100 dark:border-gray-750 pt-2 text-gray-900 dark:text-white">
                            <span>Grand Total</span>
                            <span>Rp {{ number_format($laundryOrder->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-green-600 pt-1 font-semibold">
                            <span>Sudah Dibayar</span>
                            <span>Rp {{ number_format($laundryOrder->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        @if($laundryOrder->total_price - $laundryOrder->paid_amount > 0)
                            <div class="flex justify-between text-sm text-red-600 font-bold">
                                <span>Sisa Tagihan</span>
                                <span>Rp {{ number_format($laundryOrder->total_price - $laundryOrder->paid_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($tenant)
                <!-- Tenant Contract details -->
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100 dark:border-gray-700">
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-widest font-semibold">Kontrak Kamar Kost</span>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-1">Kamar {{ $tenant->room->room_number }}</h2>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 capitalize">
                            {{ $tenant->status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-xs text-gray-500">Nama Penghuni</span>
                            <span class="block font-bold mt-0.5 text-gray-900 dark:text-white">{{ $tenant->name }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Nomor HP</span>
                            <span class="block font-bold mt-0.5 text-gray-900 dark:text-white">{{ $tenant->phone }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Biaya Sewa</span>
                            <span class="block font-bold mt-0.5 text-gray-900 dark:text-white">Rp {{ number_format($tenant->monthly_fee, 0, ',', '.') }} / bulan</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Uang Deposit</span>
                            <span class="block font-bold mt-0.5 text-gray-900 dark:text-white">Rp {{ number_format($tenant->deposit, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Date & Warnings -->
                    <div class="bg-gray-50 dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-center sm:text-left text-sm">
                            <span class="text-xs text-gray-550 block">Masa Berlaku Kontrak</span>
                            <span class="font-semibold text-gray-850 dark:text-gray-300 mt-1 block">
                                {{ $tenant->start_date->format('d M Y') }} s/d {{ $tenant->end_date->format('d M Y') }}
                            </span>
                        </div>
                        <div>
                            @if($tenant->days_remaining < 0)
                                <span class="px-4 py-2 text-xs bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 rounded-full font-bold">
                                    Lewat Tempo {{ abs($tenant->days_remaining) }} Hari
                                </span>
                            @elseif($tenant->days_remaining == 0)
                                <span class="px-4 py-2 text-xs bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 rounded-full font-bold animate-pulse">
                                    Kontrak Habis Hari Ini
                                </span>
                            @else
                                <span class="px-4 py-2 text-xs bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 rounded-full font-bold">
                                    Sisa Masa Kontrak: {{ $tenant->days_remaining }} Hari
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- No results -->
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-8 text-center text-gray-500 dark:text-gray-400 italic">
                    <i data-lucide="frown" class="h-10 w-10 text-gray-400 mx-auto mb-2"></i>
                    Data order atau sewa tidak ditemukan. Pastikan ID Order atau nomor HP yang diinput sudah benar.
                </div>
            @endif
        @endif

    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 py-6 text-center text-xs text-gray-500 transition-colors duration-200">
        <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('business_name', 'Lestari Laundry & Kost') }}</p>
    </footer>

    <script>
        // Init Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>
