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

    <!-- Charts Section -->
    <div class="grid grid-cols-1 {{ auth()->user()->isStaff() ? '' : 'lg:grid-cols-2' }} gap-6">
        @if(!auth()->user()->isStaff())
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
        @endif

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
    <div class="grid grid-cols-1 {{ auth()->user()->isStaff() ? '' : 'lg:grid-cols-2' }} gap-6">
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

        @if(!auth()->user()->isStaff())
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
        @endif
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var isDark = document.documentElement.classList.contains('dark');
        var textColour = isDark ? '#9ca3af' : '#4b5563';
        var gridColour = isDark ? '#374151' : '#e5e7eb';

        @if(!auth()->user()->isStaff())
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
        @endif

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
            
            @if(!auth()->user()->isStaff())
            incomeChart.updateOptions({
                xaxis: { labels: { style: { colors: color } } },
                yaxis: { labels: { style: { colors: color } } },
                grid: { borderColor: grid }
            });
            @endif
            laundryChart.updateOptions({
                xaxis: { labels: { style: { colors: color } } },
                yaxis: { labels: { style: { colors: color } } },
                grid: { borderColor: grid }
            });
        });
    });
</script>
@endsection
