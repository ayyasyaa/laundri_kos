<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $businessName }} | Hunian & Laundry Premium</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>

    <!-- Dark Mode Script -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased" x-data="{ mobileMenuOpen: false }">

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-white/80 dark:bg-slate-900/80 border-b border-slate-200/50 dark:border-slate-800/50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="#" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 bg-gradient-to-tr from-blue-600 to-sky-400 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/25 group-hover:scale-105 transition-transform">
                            <i data-lucide="home" class="w-5 h-5 text-white"></i>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-blue-600 to-sky-500 bg-clip-text text-transparent dark:from-blue-400 dark:to-sky-300">
                            {{ $businessName }}
                        </span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8 text-sm font-medium">
                    <a href="#laundry" class="text-slate-600 hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-400 transition-colors">Laundry</a>
                    <a href="#kost" class="text-slate-600 hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-400 transition-colors">Kost</a>
                    <a href="#kamar" class="text-slate-600 hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-400 transition-colors">Daftar Kamar</a>
                    <a href="#cek-status" class="text-slate-600 hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-400 transition-colors">Cek Status</a>
                    <a href="#kontak" class="text-slate-600 hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-400 transition-colors">Kontak</a>
                </nav>

                <!-- Actions -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-500/20 hover:shadow-blue-500/35 transition-all">
                            Dashboard
                            <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            Masuk Dashboard
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-slate-500 hover:text-slate-600 dark:text-slate-400 dark:hover:text-slate-300 focus:outline-none">
                        <i x-show="!mobileMenuOpen" data-lucide="menu" class="w-6 h-6"></i>
                        <i x-show="mobileMenuOpen" data-lucide="x" class="w-6 h-6" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" class="md:hidden border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900" x-cloak>
            <div class="px-2 pt-2 pb-4 space-y-1">
                <a href="#laundry" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-xl text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Laundry</a>
                <a href="#kost" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-xl text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Kost</a>
                <a href="#kamar" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-xl text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Daftar Kamar</a>
                <a href="#cek-status" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-xl text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Cek Status</a>
                <a href="#kontak" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-xl text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Kontak</a>
                <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-800 px-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-base font-semibold bg-blue-600 hover:bg-blue-700 text-white shadow-md">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-base font-semibold border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800">
                            Masuk Dashboard
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative overflow-hidden py-20 lg:py-32">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(59,130,246,0.08),transparent_50%)]"></div>
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <!-- Hero Content -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 mr-1.5"></i>
                        Layanan Terpadu Lestari
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-none text-slate-900 dark:text-white">
                        {{ $landingHeroTitle }}
                    </h1>
                    <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto lg:mx-0">
                        {{ $landingHeroSubtitle }}
                    </p>
                    
                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 pt-2">
                        <a href="#laundry" class="inline-flex items-center justify-center px-6 py-3.5 rounded-2xl text-base font-semibold bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-500/20 hover:shadow-blue-500/35 hover:-translate-y-0.5 transition-all">
                            <i data-lucide="wind" class="w-5 h-5 mr-2"></i>
                            Layanan Laundry
                        </a>
                        <a href="#kost" class="inline-flex items-center justify-center px-6 py-3.5 rounded-2xl text-base font-semibold bg-slate-200 dark:bg-slate-800 text-slate-900 dark:text-white hover:bg-slate-300 dark:hover:bg-slate-700 hover:-translate-y-0.5 transition-all">
                            <i data-lucide="door-open" class="w-5 h-5 mr-2"></i>
                            Pilihan Kamar Kost
                        </a>
                    </div>
                </div>

                <!-- Stats Cards & Image Placeholder -->
                <div class="lg:col-span-5 grid grid-cols-2 gap-4 sm:gap-6">
                    <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200/50 dark:border-slate-850 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-full -mr-6 -mt-6"></div>
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center mb-4">
                            <i data-lucide="home" class="w-6 h-6"></i>
                        </div>
                        <div class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white">{{ $availableRoomsCount }}</div>
                        <div class="text-xs sm:text-sm font-semibold text-slate-500 dark:text-slate-400 mt-1">Kamar Kost Kosong</div>
                        <div class="text-xs text-slate-400 dark:text-slate-500 mt-2">Dari total {{ $totalRoomsCount }} Kamar</div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200/50 dark:border-slate-850 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-sky-500/5 rounded-full -mr-6 -mt-6"></div>
                        <div class="w-12 h-12 bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 rounded-xl flex items-center justify-center mb-4">
                            <i data-lucide="package" class="w-6 h-6"></i>
                        </div>
                        <div class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white">{{ $laundryServices->count() }}</div>
                        <div class="text-xs sm:text-sm font-semibold text-slate-500 dark:text-slate-400 mt-1">Paket Layanan</div>
                        <div class="text-xs text-slate-400 dark:text-slate-500 mt-2">Aktif & siap diproses</div>
                    </div>

                    <div class="col-span-2 bg-gradient-to-r from-blue-600 to-sky-500 p-6 sm:p-8 rounded-3xl text-white shadow-lg relative overflow-hidden">
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mt-1">
                                <i data-lucide="truck" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Layanan Antar-Jemput Tersedia</h4>
                                <p class="text-xs sm:text-sm text-blue-100 mt-1">
                                    Cukup hubungi WhatsApp kami, tim kami siap mengambil cucian Anda langsung ke depan pintu rumah atau kamar kost!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Laundry Section -->
    <section id="laundry" class="py-20 bg-white dark:bg-slate-900 border-y border-slate-200/50 dark:border-slate-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400">Divisi Laundry</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white">
                    {{ $landingLaundryTitle }}
                </h2>
                <p class="text-slate-600 dark:text-slate-400">
                    {{ $landingLaundryDesc }}
                </p>
            </div>

            <!-- Features -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
                @foreach($laundryFeaturesArray as $feature)
                    @if(!empty($feature))
                        <div class="flex items-start space-x-3 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                            <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </div>
                            <span class="font-medium text-slate-700 dark:text-slate-350">{{ $feature }}</span>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Pricing Grid -->
            <div>
                <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white mb-8">Pilihan Paket & Tarif Laundry</h3>
                <div class="grid md:grid-cols-3 gap-6">
                    @forelse($laundryServices as $service)
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-3xl p-6 sm:p-8 border border-slate-200/50 dark:border-slate-750 flex flex-col justify-between hover:-translate-y-1 hover:shadow-md transition-all">
                            <div>
                                <span class="inline-flex px-3 py-1 bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 text-xs font-semibold rounded-full mb-4">
                                    {{ $service->duration_days }} Hari Pengerjaan
                                </span>
                                <h4 class="text-lg font-bold text-slate-950 dark:text-white">{{ $service->name }}</h4>
                            </div>
                            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                                <div class="text-slate-500 dark:text-slate-400 text-xs">Mulai Dari</div>
                                <div class="flex items-baseline space-x-1 mt-1">
                                    <span class="text-2xl font-extrabold text-slate-900 dark:text-white">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-450">/ Satuan/Kg</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12 text-slate-500">
                            Tidak ada data layanan laundry yang aktif.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Kost Section -->
    <section id="kost" class="py-20 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400">Divisi Kost-Kosan</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white">
                    {{ $landingKostTitle }}
                </h2>
                <p class="text-slate-600 dark:text-slate-400">
                    {{ $landingKostDesc }}
                </p>
            </div>

            <!-- Features -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-20">
                @foreach($kostFeaturesArray as $feature)
                    @if(!empty($feature))
                        <div class="flex flex-col items-center text-center p-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                            <span class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $feature }}</span>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Rooms Availability Grid -->
            <div id="kamar" class="space-y-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Peta Ketersediaan Kamar Kost</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Cek kamar yang saat ini kosong dan siap dihuni secara langsung.</p>
                    </div>
                    <div class="flex space-x-4 mt-4 sm:mt-0 text-xs font-bold">
                        <span class="flex items-center text-emerald-600"><span class="w-3 h-3 bg-emerald-500 rounded-full mr-1.5"></span> Kosong (Tersedia)</span>
                        <span class="flex items-center text-slate-500"><span class="w-3 h-3 bg-slate-300 dark:bg-slate-700 rounded-full mr-1.5"></span> Sudah Terisi</span>
                        <span class="flex items-center text-amber-600"><span class="w-3 h-3 bg-amber-500 rounded-full mr-1.5"></span> Perbaikan</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    @forelse($rooms as $room)
                        <div class="bg-white dark:bg-slate-900 border rounded-3xl p-6 flex flex-col justify-between shadow-sm relative overflow-hidden transition-all hover:shadow-md
                            @if($room->status === 'kosong') border-emerald-200 dark:border-emerald-900/50 @elseif($room->status === 'terisi') border-slate-250 dark:border-slate-800 @else border-amber-200 dark:border-amber-900/50 @endif">
                            
                            <!-- Status strip -->
                            <div class="absolute top-0 left-0 w-full h-1.5 
                                @if($room->status === 'kosong') bg-emerald-500 @elseif($room->status === 'terisi') bg-slate-300 dark:bg-slate-700 @else bg-amber-500 @endif"></div>

                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs font-semibold text-slate-450">No. Kamar</span>
                                @if($room->status === 'kosong')
                                    <span class="inline-flex px-2 py-0.5 text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-350 rounded-full uppercase">Tersedia</span>
                                @elseif($room->status === 'terisi')
                                    <span class="inline-flex px-2 py-0.5 text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full uppercase">Terisi</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 text-[10px] font-bold bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-350 rounded-full uppercase">Perbaikan</span>
                                @endif
                            </div>

                            <div class="my-4">
                                <span class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $room->room_number }}</span>
                            </div>

                            <div class="pt-3 border-t border-slate-100 dark:border-slate-800/80">
                                <span class="text-xs text-slate-400 dark:text-slate-500 block">Tarif bulanan</span>
                                <span class="text-sm font-bold text-slate-800 dark:text-slate-200">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-6 text-center py-12 text-slate-500">
                            Belum ada data kamar terdaftar di sistem.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Tracking Section (Cek Status) -->
    <section id="cek-status" class="py-20 bg-white dark:bg-slate-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-slate-900 to-blue-950 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(59,130,246,0.15),transparent_50%)]"></div>
                <div class="absolute -right-24 -top-24 w-60 h-60 bg-blue-500/10 rounded-full blur-3xl"></div>
                
                <div class="relative max-w-2xl mx-auto text-center space-y-6">
                    <div class="w-16 h-16 bg-blue-500/20 rounded-2xl flex items-center justify-center mx-auto text-blue-400 border border-blue-500/30">
                        <i data-lucide="search" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold">Cek Status Laundry atau Kost</h3>
                    <p class="text-slate-350 text-sm sm:text-base">
                        Apakah Anda pelanggan laundry yang ingin melacak pakaian Anda, atau penghuni kost yang ingin memeriksa tagihan sewa? Masukkan nomor order laundry atau nama/no telepon Anda di bawah.
                    </p>

                    <form action="{{ route('portal.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 pt-2">
                        <input type="text" 
                               name="search" 
                               required
                               placeholder="Contoh: ORD-20260712-002 atau Nama Anda" 
                               class="flex-1 px-5 py-4 rounded-2xl bg-white/10 border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-4 bg-blue-600 hover:bg-blue-700 font-bold rounded-2xl text-sm transition-colors shadow-lg shadow-blue-600/35 shrink-0">
                            Cari di Portal
                            <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact & Map Section -->
    <section id="kontak" class="py-20 bg-slate-50 dark:bg-slate-950 border-t border-slate-200/50 dark:border-slate-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-start">
                
                <!-- Contact Info -->
                <div class="space-y-8">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400">Hubungi Kami</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-2">Ada Pertanyaan? Kami Siap Membantu</h2>
                        <p class="text-slate-600 dark:text-slate-400 mt-4">
                            Butuh info lebih lanjut mengenai ketersediaan kamar sewa, sewa bulanan, atau ingin memesan jasa kurir laundry? Jangan ragu untuk menghubungi kami.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <!-- Phone/WA -->
                        <div class="flex items-center space-x-4 bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-100 dark:border-slate-850 shadow-sm">
                            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-450 rounded-xl flex items-center justify-center shrink-0">
                                <i data-lucide="phone" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-xs text-slate-400 uppercase">WhatsApp & Telepon</h4>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $landingWhatsapp) }}?text=Halo%20{{ urlencode($businessName) }},%20saya%20tertarik%20dengan%20layanan%20Anda." target="_blank" class="font-extrabold text-slate-900 dark:text-white hover:text-emerald-500 transition-colors">
                                    {{ $landingWhatsapp }}
                                </a>
                            </div>
                        </div>

                        <!-- Instagram -->
                        <div class="flex items-center space-x-4 bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-100 dark:border-slate-850 shadow-sm">
                            <div class="w-12 h-12 bg-pink-100 dark:bg-pink-950 text-pink-600 dark:text-pink-400 rounded-xl flex items-center justify-center shrink-0">
                                <i data-lucide="instagram" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-xs text-slate-400 uppercase">Instagram</h4>
                                <a href="https://instagram.com/{{ $landingInstagram }}" target="_blank" class="font-extrabold text-slate-900 dark:text-white hover:text-pink-500 transition-colors">
                                    @{{ $landingInstagram }}
                                </a>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="flex items-center space-x-4 bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-100 dark:border-slate-850 shadow-sm">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center shrink-0">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-xs text-slate-400 uppercase">Alamat Usaha</h4>
                                <p class="font-extrabold text-slate-900 dark:text-white">{{ $businessAddress }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Illustrative details or Interactive Map -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-100 dark:border-slate-800 shadow-sm space-y-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Kenapa Memilih Kami?</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="shield" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-sm text-slate-900 dark:text-white">Keamanan & Privasi Terjamin</h5>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kost dilengkapi dengan CCTV 24 jam dan akses terbatas, cucian laundry diproses secara individu.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-sm text-slate-900 dark:text-white">Proses Laundry Cepat</h5>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Layanan laundry selesai tepat waktu dengan pilihan pengerjaan kilat ekspres untuk kebutuhan mendadak Anda.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="smile" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-sm text-slate-900 dark:text-white">Pelayanan Ramah & Responsif</h5>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Tim admin dan staff kami selalu siap merespons pertanyaan dan permintaan Anda dengan senang hati.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $landingWhatsapp) }}?text=Halo%20{{ urlencode($businessName) }},%2520saya%2520ingin%2520tanya%2520ketersediaan%2520kamar%2520kost" target="_blank" class="w-full inline-flex items-center justify-center px-6 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl text-sm transition-colors shadow-lg shadow-emerald-600/25">
                            <i data-lucide="message-circle" class="w-5 h-5 mr-2"></i>
                            Chat Langsung Via WhatsApp
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <p class="text-sm">
                &copy; {{ date('Y') }} <strong>{{ $businessName }}</strong>. Semua Hak Cipta Dilindungi.
            </p>
            <p class="text-xs text-slate-500">
                Alamat: {{ $businessAddress }} | Telepon: {{ $businessPhone }}
            </p>
        </div>
    </footer>

    <!-- Init Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
