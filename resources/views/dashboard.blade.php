@extends('layouts.app')

@section('title', 'Dashboard Monitoring')

@section('content')
<div class="space-y-8">
    <!-- Header Welcome Card -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-800 rounded-2xl p-6 md:p-8 shadow-lg text-white">
        <h2 class="text-2xl md:text-3xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h2>
        <p class="mt-2 text-blue-100 font-medium">Berikut ringkasan operasional bisnis laundry dan kost Anda hari ini.</p>
    </div>

    <!-- KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 {{ auth()->user()->isStaff() ? 'lg:grid-cols-3' : 'lg:grid-cols-4' }} gap-6">
        <!-- Card 1: Total Laundry Hari Ini -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Laundry Hari Ini</p>
                    <h3 class="text-2xl font-bold mt-2 text-gray-900 dark:text-white">{{ $totalLaundryToday }}</h3>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
                    <i data-lucide="shopping-bag" class="h-6 w-6"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 flex items-center gap-1">
                <span class="text-green-500 font-semibold"><i data-lucide="arrow-up" class="inline h-3 w-3"></i> Baru</span> hari ini
            </p>
        </div>

        <!-- Card 2: Laundry Diproses -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Laundry Diproses</p>
                    <h3 class="text-2xl font-bold mt-2 text-gray-900 dark:text-white">{{ $laundryProcesses }}</h3>
                </div>
                <div class="p-3 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl">
                    <i data-lucide="loader-2" class="h-6 w-6 animate-spin"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-4">Sedang dikerjakan staff</p>
        </div>

        <!-- Card 3: Laundry Selesai -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Laundry Selesai</p>
                    <h3 class="text-2xl font-bold mt-2 text-gray-900 dark:text-white">{{ $laundryCompleted }}</h3>
                </div>
                <div class="p-3 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-xl">
                    <i data-lucide="check-circle" class="h-6 w-6"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-4">Menunggu serah terima</p>
        </div>

        @if(!auth()->user()->isStaff())
        <!-- Card 4: Kamar Terisi / Kosong -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Kamar Kost</p>
                    <h3 class="text-xl font-bold mt-2 text-gray-900 dark:text-white">
                        <span class="text-blue-600 dark:text-blue-400">{{ $roomsOccupied }}</span> Terisi / 
                        <span class="text-gray-500">{{ $roomsEmpty }}</span> Kosong
                    </h3>
                </div>
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl">
                    <i data-lucide="home" class="h-6 w-6"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-4">Total Kamar Kost</p>
        </div>
        @endif
    </div>

    @if(!auth()->user()->isStaff())
    <!-- Financial KPI Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Pendapatan Laundry -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
                    <i data-lucide="wallet" class="h-6 w-6"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Pendapatan Laundry</p>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">Rp {{ number_format($revenueLaundry, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Pendapatan Kost -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-xl">
                    <i data-lucide="banknote" class="h-6 w-6"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Pendapatan Kost</p>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">Rp {{ number_format($revenueKost, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Gabungan -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl border border-slate-800 p-6 shadow-md hover:shadow-lg transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-500/20 text-blue-400 rounded-xl">
                    <i data-lucide="combine" class="h-6 w-6"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Pendapatan Gabungan</p>
                    <h3 class="text-xl font-bold text-blue-400 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(!auth()->user()->isStaff())
    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Income Trend -->
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="trending-up" class="h-5 w-5 text-blue-600"></i>
                    Tren Pendapatan Harian
                </h3>
            </div>
            <div id="income-chart" class="w-full"></div>
        </div>

        <!-- Weekly Laundry Orders -->
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="h-5 w-5 text-indigo-600"></i>
                    Order Laundry Mingguan
                </h3>
            </div>
            <div id="laundry-chart" class="w-full"></div>
        </div>
    </div>

    <!-- Tasks & Reminders Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Laundry tasks -->
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="sparkles" class="text-blue-500 h-5 w-5"></i>
                Tugas Operasional Laundry
            </h3>
            
            <div class="space-y-4">
                <!-- Subcategory: Ready to deliver -->
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Siap Diantar (Delivery)</h4>
                    @if($readyToDeliver->count() === 0)
                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">Tidak ada antrean pengantaran laundry.</p>
                    @else
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($readyToDeliver as $order)
                                <div class="py-2.5 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $order->order_number }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Customer: {{ $order->customer->name }} ({{ $order->customer->phone }})</p>
                                    </div>
                                    <span class="text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 px-2 py-0.5 rounded-full font-medium">Ready</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Subcategory: Unclaimed -->
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Belum Diambil</h4>
                    @if($unclaimedLaundry->count() === 0)
                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">Semua laundry selesai sudah diambil.</p>
                    @else
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($unclaimedLaundry as $order)
                                <div class="py-2.5 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $order->order_number }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Customer: {{ $order->customer->name }} ({{ $order->customer->phone }})</p>
                                    </div>
                                    <span class="text-xs bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 px-2 py-0.5 rounded-full font-medium">Belum Diambil</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Subcategory: Unpaid laundry -->
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Pembayaran Belum Lunas</h4>
                    @if($unpaidLaundry->count() === 0)
                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">Semua pembayaran lunas.</p>
                    @else
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($unpaidLaundry as $order)
                                <div class="py-2.5 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $order->order_number }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Customer: {{ $order->customer->name }} - Sisa: Rp {{ number_format($order->total_price - $order->paid_amount, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="text-xs bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 px-2 py-0.5 rounded-full font-medium capitalize">{{ $order->payment_status }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Boarding house warnings -->
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="bell" class="text-amber-500 h-5 w-5"></i>
                Jatuh Tempo Kontrak Kost (≤ 7 Hari)
            </h3>
            
            @if($dueTenants->count() === 0)
                <div class="p-6 text-center text-gray-500 dark:text-gray-400 italic text-sm">
                    Tidak ada penghuni kost yang mendekati jatuh tempo kontrak dalam 7 hari ini.
                </div>
            @else
                <div class="space-y-4 max-h-[350px] overflow-y-auto pr-1">
                    @foreach($dueTenants as $tenant)
                        <div class="p-4 rounded-xl border {{ $tenant->reminder_status === 'overdue' ? 'bg-red-50/50 border-red-200 dark:bg-red-900/20 dark:border-red-800' : 'bg-amber-50/50 border-amber-200 dark:bg-amber-900/20 dark:border-amber-800' }} flex justify-between items-center">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-gray-800 dark:text-gray-100 text-sm">Kamar {{ $tenant->room->room_number }}</span>
                                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">- {{ $tenant->name }}</span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Rent: Rp {{ number_format($tenant->monthly_fee, 0, ',', '.') }} / bulan</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Tempo: {{ $tenant->end_date->format('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                @if($tenant->days_remaining < 0)
                                    <span class="text-xs bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 font-bold px-2.5 py-1 rounded-full">
                                        Lewat {{ abs($tenant->days_remaining) }} Hari
                                    </span>
                                @elseif($tenant->days_remaining == 0)
                                    <span class="text-xs bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 font-bold px-2.5 py-1 rounded-full animate-pulse">
                                        Hari H!
                                    </span>
                                @else
                                    <span class="text-xs bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 font-bold px-2.5 py-1 rounded-full">
                                        Sisa {{ $tenant->days_remaining }} Hari
                                    </span>
                                @endif
                                <div class="mt-2 text-xs">
                                    <a href="https://wa.me/{{ $tenant->phone }}" target="_blank" class="inline-flex items-center gap-1 text-green-600 dark:text-green-400 font-semibold hover:underline">
                                        <i data-lucide="message-square" class="h-3.5 w-3.5"></i> Hubungi
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @else
    <!-- Staff Task Queue Center -->
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="text-blue-600 h-6 w-6"></i>
                    Pusat Antrean Tugas (Task Center)
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola dan selesaikan seluruh cucian masuk secara tepat waktu.</p>
            </div>
            <div>
                <a href="{{ route('laundry.orders.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-sm transition-all duration-200">
                    <i data-lucide="plus-circle" class="h-4 w-4"></i> Buat Order Baru
                </a>
            </div>
        </div>

        <div x-data="{ activeTab: 'baru' }" class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden transition-all duration-200">
            <!-- Tabs Navigation -->
            <div class="flex border-b border-gray-150 dark:border-gray-750 bg-gray-50/50 dark:bg-gray-900/10">
                <button @click="activeTab = 'baru'" 
                        :class="activeTab === 'baru' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400 bg-white dark:bg-gray-800' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                        class="flex-1 py-4 px-6 text-center font-bold text-sm border-b-2 transition-all duration-200 flex items-center justify-center gap-2">
                    <i data-lucide="inbox" class="h-4 w-4"></i>
                    Antrean Baru
                    <span class="px-2 py-0.5 text-xs rounded-full font-bold bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 ml-1">
                        {{ $newOrders->count() }}
                    </span>
                </button>
                <button @click="activeTab = 'proses'" 
                        :class="activeTab === 'proses' ? 'border-amber-500 text-amber-600 dark:text-amber-400 dark:border-amber-400 bg-white dark:bg-gray-800' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                        class="flex-1 py-4 px-6 text-center font-bold text-sm border-b-2 transition-all duration-200 flex items-center justify-center gap-2">
                    <i data-lucide="cog" class="h-4 w-4 text-amber-500"></i>
                    Sedang Diproses
                    <span class="px-2 py-0.5 text-xs rounded-full font-bold bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 ml-1">
                        {{ $processingOrders->count() }}
                    </span>
                </button>
                <button @click="activeTab = 'selesai'" 
                        :class="activeTab === 'selesai' ? 'border-green-500 text-green-600 dark:text-green-400 dark:border-green-400 bg-white dark:bg-gray-800' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                        class="flex-1 py-4 px-6 text-center font-bold text-sm border-b-2 transition-all duration-200 flex items-center justify-center gap-2">
                    <i data-lucide="truck" class="h-4 w-4 text-green-500"></i>
                    Siap Serah Terima & Delivery
                    <span class="px-2 py-0.5 text-xs rounded-full font-bold bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 ml-1">
                        {{ $completedOrders->count() }}
                    </span>
                </button>
            </div>

            <!-- Tabs Content -->
            <div class="p-6">
                <!-- Tab 1: New Orders -->
                <div x-show="activeTab === 'baru'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" class="space-y-4">
                    @if($newOrders->count() === 0)
                        <div class="p-8 text-center">
                            <i data-lucide="sparkles" class="h-12 w-12 text-blue-300 dark:text-blue-900/30 mx-auto mb-3"></i>
                            <h4 class="font-bold text-gray-700 dark:text-gray-300">Belum ada order masuk!</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Cucian baru yang didaftarkan akan muncul di sini.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($newOrders as $order)
                                <div class="bg-gray-50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4 hover:shadow transition-all duration-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="text-xs font-bold text-blue-655 dark:text-blue-400 tracking-wide bg-blue-50 dark:bg-blue-900/20 px-2.5 py-1 rounded-xl">{{ $order->order_number }}</span>
                                                @if($order->status === 'pending')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-850 dark:bg-gray-700 dark:text-gray-300">
                                                        Pending
                                                    </span>
                                                @elseif($order->status === 'sedang_diambil')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-yellow-100 text-yellow-850 dark:bg-yellow-900/40 dark:text-yellow-300">
                                                        Sedang Diambil
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-850 dark:bg-blue-900/40 dark:text-blue-300">
                                                        Baru
                                                    </span>
                                                @endif
                                            </div>
                                            <h4 class="font-bold text-gray-800 dark:text-white mt-3">{{ $order->customer->name }}</h4>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 block mt-0.5">{{ $order->customer->phone }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Masuk: {{ $order->created_at->format('d M, H:i') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-3 border-t border-gray-150 dark:border-gray-800 grid grid-cols-2 gap-2 text-xs text-gray-650 dark:text-gray-400">
                                        <div>
                                            <span class="block text-gray-400">Layanan:</span>
                                            <span class="font-bold text-gray-800 dark:text-gray-200 mt-0.5 block">{{ $order->service->name }} ({{ $order->weight }} kg)</span>
                                        </div>
                                        <div>
                                            <span class="block text-gray-400">Pengantaran:</span>
                                            <span class="font-bold text-gray-800 dark:text-gray-200 mt-0.5 block capitalize">{{ str_replace('_', ' & ', $order->delivery_type) }}</span>
                                        </div>
                                    </div>

                                    @if($order->notes)
                                        <div class="text-xs bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-850 p-3 rounded-xl text-gray-500 dark:text-gray-400 italic">
                                            Catatan: "{{ $order->notes }}"
                                        </div>
                                    @endif

                                    <div class="pt-3 border-t border-gray-150 dark:border-gray-800 flex justify-between items-center gap-4">
                                        <div>
                                            <span class="block text-[10px] text-gray-400 uppercase font-bold tracking-wider">Tenggat Waktu</span>
                                            <span class="countdown-badge font-semibold px-2.5 py-1 rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm mt-1" data-estimation="{{ $order->estimation_date ? $order->estimation_date->toIso8601String() : '' }}">
                                                <i data-lucide="clock" class="h-3.5 w-3.5 flex-shrink-0"></i>
                                                <span class="countdown-text">--:--:--</span>
                                            </span>
                                        </div>
                                        <div class="inline-flex gap-2">
                                            @if($order->status === 'pending')
                                                <form method="POST" action="{{ route('laundry.orders.updateStatus', $order) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="sedang_diambil">
                                                    <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-bold text-xs rounded-xl transition-all shadow hover:shadow-yellow-500/20 flex items-center gap-1.5" title="Mulai Penjemputan">
                                                        <i data-lucide="truck" class="h-3.5 w-3.5"></i> Jemput Cucian
                                                    </button>
                                                </form>
                                            @elseif($order->status === 'sedang_diambil')
                                                <form method="POST" action="{{ route('laundry.orders.updateStatus', $order) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="baru">
                                                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition-all shadow hover:shadow-blue-500/20 flex items-center gap-1.5" title="Selesai Dijemput (Tiba di Toko)">
                                                        <i data-lucide="archive-restore" class="h-3.5 w-3.5"></i> Tiba di Toko
                                                    </button>
                                                </form>
                                            @elseif($order->status === 'baru')
                                                <form method="POST" action="{{ route('laundry.orders.updateStatus', $order) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="proses">
                                                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition-all shadow hover:shadow-blue-500/20 flex items-center gap-1.5">
                                                        <i data-lucide="play" class="h-3.5 w-3.5"></i> Mulai Proses
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Tab 2: Processing Orders -->
                <div x-show="activeTab === 'proses'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" class="space-y-4">
                    @if($processingOrders->count() === 0)
                        <div class="p-8 text-center">
                            <i data-lucide="check-circle" class="h-12 w-12 text-green-300 dark:text-green-900/30 mx-auto mb-3"></i>
                            <h4 class="font-bold text-gray-700 dark:text-gray-300">Antrean cuci beres!</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tidak ada cucian yang sedang diproses saat ini.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($processingOrders as $order)
                                <div class="bg-gray-50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4 hover:shadow transition-all duration-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span class="text-xs font-bold text-amber-600 dark:text-amber-400 tracking-wide bg-amber-50 dark:bg-amber-900/20 px-2.5 py-1 rounded-xl">{{ $order->order_number }}</span>
                                            <h4 class="font-bold text-gray-800 dark:text-white mt-3">{{ $order->customer->name }}</h4>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 block mt-0.5">{{ $order->customer->phone }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Mulai: {{ $order->updated_at->format('d M, H:i') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-3 border-t border-gray-150 dark:border-gray-800 grid grid-cols-2 gap-2 text-xs text-gray-655 dark:text-gray-400">
                                        <div>
                                            <span class="block text-gray-400">Layanan:</span>
                                            <span class="font-bold text-gray-800 dark:text-gray-200 mt-0.5 block">{{ $order->service->name }} ({{ $order->weight }} kg)</span>
                                        </div>
                                        <div>
                                            <span class="block text-gray-400">Pengantaran:</span>
                                            <span class="font-bold text-gray-800 dark:text-gray-200 mt-0.5 block capitalize">{{ str_replace('_', ' & ', $order->delivery_type) }}</span>
                                        </div>
                                    </div>

                                    @if($order->notes)
                                        <div class="text-xs bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-855 p-3 rounded-xl text-gray-500 dark:text-gray-400 italic">
                                            Catatan: "{{ $order->notes }}"
                                        </div>
                                    @endif

                                    <div class="pt-3 border-t border-gray-150 dark:border-gray-800 flex justify-between items-center gap-4">
                                        <div>
                                            <span class="block text-[10px] text-gray-400 uppercase font-bold tracking-wider">Tenggat Waktu</span>
                                            <span class="countdown-badge font-semibold px-2.5 py-1 rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm mt-1" data-estimation="{{ $order->estimation_date ? $order->estimation_date->toIso8601String() : '' }}">
                                                <i data-lucide="clock" class="h-3.5 w-3.5 flex-shrink-0"></i>
                                                <span class="countdown-text">--:--:--</span>
                                            </span>
                                        </div>
                                        <form method="POST" action="{{ route('laundry.orders.updateStatus', $order) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold text-xs rounded-xl transition-all shadow hover:shadow-green-500/20 flex items-center gap-1.5">
                                                <i data-lucide="check-circle" class="h-3.5 w-3.5"></i> Selesai Cuci
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Tab 3: Completed/Delivery Queue -->
                <div x-show="activeTab === 'selesai'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" class="space-y-4">
                    @if($completedOrders->count() === 0)
                        <div class="p-8 text-center">
                            <i data-lucide="check-circle-2" class="h-12 w-12 text-slate-300 dark:text-slate-900/30 mx-auto mb-3"></i>
                            <h4 class="font-bold text-gray-700 dark:text-gray-300">Antrean serah terima kosong.</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Order yang selesai dicuci siap diserahkan akan tampil di sini.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($completedOrders as $order)
                                <div class="bg-gray-50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4 hover:shadow transition-all duration-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="text-xs font-bold text-green-600 dark:text-green-400 tracking-wide bg-green-50 dark:bg-green-900/20 px-2.5 py-1 rounded-xl">{{ $order->order_number }}</span>
                                                @if($order->status === 'sedang_dikirim')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-100 text-indigo-850 dark:bg-indigo-900/40 dark:text-indigo-300">
                                                        Sedang Dikirim
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-850 dark:bg-green-900/40 dark:text-green-300">
                                                        Selesai Cuci
                                                    </span>
                                                @endif
                                            </div>
                                            <h4 class="font-bold text-gray-800 dark:text-white mt-3">{{ $order->customer->name }}</h4>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 block mt-0.5">{{ $order->customer->phone }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Total Tagihan:</span>
                                            <p class="font-bold text-sm text-gray-800 dark:text-white mt-0.5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-3 border-t border-gray-150 dark:border-gray-800 grid grid-cols-2 gap-2 text-xs text-gray-650 dark:text-gray-400">
                                        <div>
                                            <span class="block text-gray-400">Pengantaran:</span>
                                            <span class="font-bold text-gray-800 dark:text-gray-200 mt-0.5 block capitalize">{{ str_replace('_', ' & ', $order->delivery_type) }}</span>
                                        </div>
                                        <div>
                                            <span class="block text-gray-400">Status Pembayaran:</span>
                                            <span class="font-semibold text-[10px] uppercase px-2 py-0.5 rounded-md inline-block mt-0.5 
                                                @if($order->payment_status === 'lunas') 
                                                    bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 
                                                @elseif($order->payment_status === 'dp')
                                                    bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400
                                                @else
                                                    bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                                @endif">
                                                {{ strtoupper($order->payment_status) }}
                                            </span>
                                        </div>
                                    </div>

                                                                    @if($order->payment_status !== 'lunas')
                                        <div class="p-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/50 rounded-xl text-xs text-red-800 dark:text-red-450 flex justify-between items-center gap-2">
                                            <span class="font-semibold">Sisa: Rp {{ number_format($order->total_price - $order->paid_amount, 0, ',', '.') }}</span>
                                            <a href="{{ route('laundry.orders.show', $order) }}" class="px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold text-[10px] uppercase shadow-sm transition-colors">
                                                Bayar & Lunasi
                                            </a>
                                        </div>
                                    @endif

                                    <div class="pt-3 border-t border-gray-150 dark:border-gray-800 flex justify-between items-center gap-4">
                                        <a href="{{ route('laundry.orders.show', $order) }}" class="text-xs text-blue-600 hover:underline dark:text-blue-400 flex items-center gap-1 font-semibold">
                                            <i data-lucide="eye" class="h-4 w-4"></i> Rincian Order
                                        </a>
                                        <div class="inline-flex gap-2">
                                            @php
                                                $hasDelivery = in_array($order->delivery_type, ['delivery', 'pickup_delivery']);
                                                $deliveryCompleted = $order->deliveries->where('type', 'delivery')->where('status', 'completed')->isNotEmpty();
                                            @endphp

                                            @if($order->status === 'sedang_dikirim')
                                                <form method="POST" action="{{ route('laundry.orders.updateStatus', $order) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="diambil_diantar">
                                                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold text-xs rounded-xl transition-all shadow hover:shadow-green-500/20 flex items-center gap-1.5" title="Selesai Diantar / Diterima">
                                                        <i data-lucide="package-check" class="h-3.5 w-3.5"></i> Selesai Diantar
                                                    </button>
                                                </form>
                                            @elseif($order->status === 'selesai' && $hasDelivery && !$deliveryCompleted)
                                                <form method="POST" action="{{ route('laundry.orders.updateStatus', $order) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="sedang_dikirim">
                                                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-all shadow hover:shadow-indigo-500/20 flex items-center gap-1.5" title="Mulai Pengantaran">
                                                        <i data-lucide="truck" class="h-3.5 w-3.5"></i> Kirim Cucian
                                                    </button>
                                                </form>
                                            @elseif($order->status === 'selesai' && (!$hasDelivery || $deliveryCompleted))
                                                @if($order->payment_status === 'lunas')
                                                    <form method="POST" action="{{ route('laundry.orders.updateStatus', $order) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="diambil_diantar">
                                                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition-all shadow hover:shadow-emerald-500/20 flex items-center gap-1.5">
                                                            <i data-lucide="check" class="h-3.5 w-3.5"></i> Serah Terima
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">Harap lunasi pembayaran</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(!auth()->user()->isStaff())
        var isDark = document.documentElement.classList.contains('dark');
        var textColour = isDark ? '#9ca3af' : '#4b5563';
        var gridColour = isDark ? '#374151' : '#e5e7eb';

        // 1. Income Trend Chart
        var dailyLabels = @json($dailyIncomeLabels);
        var dailyValues = @json($dailyIncomeValues);
        
        var incomeOptions = {
            chart: {
                type: 'area',
                height: 300,
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            colors: ['#3b82f6'],
            series: [{
                name: 'Pendapatan',
                data: dailyValues
            }],
            xaxis: {
                categories: dailyLabels,
                labels: { style: { colors: textColour } }
            },
            yaxis: {
                labels: {
                    style: { colors: textColour },
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            },
            grid: { borderColor: gridColour },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 100]
                }
            }
        };

        var incomeChart = new ApexCharts(document.querySelector("#income-chart"), incomeOptions);
        incomeChart.render();

        // 2. Weekly Laundry Orders Chart
        var weeklyLabels = @json($weeklyOrdersLabels);
        var weeklyValues = @json($weeklyOrdersValues);

        var laundryOptions = {
            chart: {
                type: 'bar',
                height: 300,
                toolbar: { show: false }
            },
            colors: ['#6366f1'],
            series: [{
                name: 'Jumlah Order',
                data: weeklyValues
            }],
            xaxis: {
                categories: weeklyLabels,
                labels: { style: { colors: textColour } }
            },
            yaxis: {
                labels: { style: { colors: textColour } }
            },
            grid: { borderColor: gridColour },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '50%'
                }
            },
            dataLabels: { enabled: false }
        };

        var laundryChart = new ApexCharts(document.querySelector("#laundry-chart"), laundryOptions);
        laundryChart.render();
        
        // Listen to dark mode changes to update chart styling
        window.addEventListener('color-theme-changed', function() {
            var dark = document.documentElement.classList.contains('dark');
            var color = dark ? '#9ca3af' : '#4b5563';
            var grid = dark ? '#374151' : '#e5e7eb';
            
            incomeChart.updateOptions({
                xaxis: { labels: { style: { colors: color } } },
                yaxis: { labels: { style: { colors: color } } },
                grid: { borderColor: grid }
            });
            laundryChart.updateOptions({
                xaxis: { labels: { style: { colors: color } } },
                yaxis: { labels: { style: { colors: color } } },
                grid: { borderColor: grid }
            });
        });
        @else
        // 3. Countdown Timers for Tasks (Staff center)
        function updateCountdowns() {
            const now = new Date();
            document.querySelectorAll('.countdown-badge').forEach(el => {
                const estStr = el.getAttribute('data-estimation');
                const textEl = el.querySelector('.countdown-text');
                if (!estStr) {
                    textEl.innerText = 'Tanpa Estimasi';
                    el.className = 'countdown-badge font-semibold px-2 py-0.5 rounded-md text-[11px] inline-flex items-center gap-1 shadow-sm mt-1 bg-gray-150 text-gray-500 dark:bg-gray-800 dark:text-gray-400';
                    return;
                }
                const estDate = new Date(estStr);
                const diff = estDate - now;

                if (diff <= 0) {
                    // Overdue
                    const absDiff = Math.abs(diff);
                    const hours = Math.floor(absDiff / 3600000);
                    const minutes = Math.floor((absDiff % 3600000) / 60000);
                    const seconds = Math.floor((absDiff % 60000) / 1000);
                    textEl.innerText = `Lewat ${hours}j ${minutes}m ${seconds}s`;
                    el.className = 'countdown-badge font-semibold px-2 py-0.5 rounded-md text-[11px] inline-flex items-center gap-1 shadow-sm mt-1 bg-red-105 text-red-700 dark:bg-red-950/20 dark:text-red-400 animate-pulse';
                } else {
                    // Remaining time
                    const hours = Math.floor(diff / 3600000);
                    const minutes = Math.floor((diff % 3600000) / 60000);
                    const seconds = Math.floor((diff % 60000) / 1000);
                    textEl.innerText = `Sisa ${hours}j ${minutes}m ${seconds}s`;
                    if (hours < 3) {
                        el.className = 'countdown-badge font-semibold px-2 py-0.5 rounded-md text-[11px] inline-flex items-center gap-1 shadow-sm mt-1 bg-amber-100 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400';
                    } else {
                        el.className = 'countdown-badge font-semibold px-2 py-0.5 rounded-md text-[11px] inline-flex items-center gap-1 shadow-sm mt-1 bg-green-100 text-green-700 dark:bg-green-950/20 dark:text-green-400';
                    }
                }
            });
        }
        setInterval(updateCountdowns, 1000);
        updateCountdowns(); // initial run
        @endif
    });
</script>
@endsection
