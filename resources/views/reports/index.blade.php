@extends('layouts.app')

@section('title', 'Laporan Keuangan & Operasional')

@section('content')
<div class="space-y-6">
    <!-- Header (Screen Only) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 print:hidden">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Laporan Keuangan & Operasional Bulanan</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Analisis kinerja keuangan, laba rugi, arus kas, dan piutang usaha sesuai standar akuntansi.</p>
        </div>
        <div>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm shadow-sm transition-colors">
                <i data-lucide="printer" class="h-4 w-4"></i> Cetak Laporan (PDF)
            </button>
        </div>
    </div>

    <!-- Month & Year Filter Card (Screen Only) -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-150 dark:border-gray-700 p-5 shadow-sm print:hidden">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col sm:flex-row items-end gap-4">
            <div class="w-full sm:w-48">
                <label for="month" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Pilih Bulan</label>
                <select name="month" id="month" class="block w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-700 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-950 dark:text-white text-xs font-semibold">
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-36">
                <label for="year" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Pilih Tahun</label>
                <select name="year" id="year" class="block w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-250 dark:border-gray-700 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-950 dark:text-white text-xs font-semibold">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-gray-950 hover:bg-gray-900 text-white font-bold rounded-xl text-xs shadow-sm transition-colors">
                    Terapkan Laporan
                </button>
                <a href="{{ route('reports.index') }}" class="w-full sm:w-auto px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-650 dark:text-gray-200 rounded-xl text-xs font-semibold transition-colors text-center">
                    Bulan Ini
                </a>
            </div>
        </form>
    </div>

    <!-- Printable Report Document Cover (Only visible on print) -->
    <div class="hidden print:block text-center border-b-2 border-gray-900 pb-5 mb-8">
        <h1 class="text-2xl font-bold uppercase text-gray-900 tracking-wide">{{ \App\Models\Setting::get('business_name', 'Lestari Laundry & Kost') }}</h1>
        <p class="text-xs text-gray-600 mt-1">{{ \App\Models\Setting::get('business_address', 'Jakarta, Indonesia') }}</p>
        <div class="mt-5">
            <h2 class="text-lg font-bold uppercase tracking-wider text-gray-800">LAPORAN KEUANGAN BULANAN LENGKAP</h2>
            <p class="text-sm font-semibold text-gray-600 mt-1">Periode: {{ $months[$month] }} {{ $year }}</p>
        </div>
        <p class="text-[10px] text-gray-400 mt-4 text-right">Dicetak pada: {{ date('d M Y, H:i') }} | Operator: {{ auth()->user()->name }}</p>
    </div>

    <!-- Tabbed Container -->
    <div x-data="{ activeTab: 'summary' }" class="space-y-6">
        <!-- Tabs Navigation (Screen Only) -->
        <div class="flex border-b border-gray-150 dark:border-gray-750 print:hidden overflow-x-auto bg-white dark:bg-gray-800 rounded-2xl p-1.5 shadow-sm gap-1">
            <button @click="activeTab = 'summary'" 
                    :class="activeTab === 'summary' ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-gray-500 hover:text-gray-750 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-900/40'"
                    class="py-2.5 px-4.5 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-2 whitespace-nowrap">
                <i data-lucide="layout-dashboard" class="h-4 w-4"></i> Ringkasan Bisnis
            </button>
            <button @click="activeTab = 'labarugi'" 
                    :class="activeTab === 'labarugi' ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-gray-500 hover:text-gray-750 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-900/40'"
                    class="py-2.5 px-4.5 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-2 whitespace-nowrap">
                <i data-lucide="file-text" class="h-4 w-4"></i> Laporan Laba Rugi
            </button>
            <button @click="activeTab = 'cashflow'" 
                    :class="activeTab === 'cashflow' ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-gray-500 hover:text-gray-750 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-900/40'"
                    class="py-2.5 px-4.5 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-2 whitespace-nowrap">
                <i data-lucide="wallet" class="h-4 w-4"></i> Laporan Arus Kas
            </button>
            <button @click="activeTab = 'piutang'" 
                    :class="activeTab === 'piutang' ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-gray-500 hover:text-gray-750 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-900/40'"
                    class="py-2.5 px-4.5 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-2 whitespace-nowrap">
                <i data-lucide="book-open" class="h-4 w-4"></i> Buku Piutang
                @if($totalReceivables > 0)
                    <span class="px-1.5 py-0.5 text-[9px] rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 font-extrabold ml-0.5">
                        Ada
                    </span>
                @endif
            </button>
        </div>

        <!-- 1. Ringkasan Bisnis Tab -->
        <div x-show="activeTab === 'summary'" class="tab-content-item space-y-6">
            <!-- Executive Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-2xl p-5 shadow-sm print:bg-white print:border-gray-300">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pemasukan</span>
                    <span class="block text-xl font-bold mt-2 text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-2xl p-5 shadow-sm print:bg-white print:border-gray-300">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pengeluaran</span>
                    <span class="block text-xl font-bold mt-2 text-red-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                </div>
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl p-5 shadow-md print:bg-white print:text-black print:border print:border-gray-300">
                    <span class="text-xs font-semibold text-slate-400 print:text-gray-500 uppercase tracking-wider">Laba Bersih (Profit)</span>
                    <span class="block text-xl font-bold mt-2 text-blue-400 print:text-blue-600">Rp {{ number_format($profit, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Laundry Operation Summary -->
            <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-2xl p-6 shadow-sm print:border-gray-300">
                <div class="flex justify-between items-center pb-4 border-b border-gray-100 dark:border-gray-700 print:border-gray-300">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="shopping-cart" class="h-5 w-5 text-blue-600 print:hidden"></i>
                        Ringkasan Laundry ({{ $months[$month] }} {{ $year }})
                    </h3>
                    <div class="print:hidden">
                        <a href="{{ route('reports.export', ['type' => 'laundry', 'month' => $month, 'year' => $year]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 dark:text-blue-400 text-xs font-bold rounded-xl transition-colors">
                            <i data-lucide="download" class="h-3.5 w-3.5"></i> Ekspor CSV
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-5 text-sm">
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 print:bg-white print:border-gray-200">
                        <span class="text-xs text-gray-500">Order Laundry Masuk</span>
                        <span class="block text-xl font-bold mt-1 text-gray-900 dark:text-white">{{ $laundryOrdersCount }} Order</span>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 print:bg-white print:border-gray-200">
                        <span class="text-xs text-gray-500">Volume Cucian</span>
                        <span class="block text-xl font-bold mt-1 text-gray-900 dark:text-white">{{ number_format($laundryTotalWeight, 1, ',', '.') }} kg</span>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 print:bg-white print:border-gray-200">
                        <span class="text-xs text-gray-500">Omzet Laundry Masuk Kas</span>
                        <span class="block text-xl font-bold mt-1 text-green-600">Rp {{ number_format($laundryTotalRevenue, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Boarding House Operation Summary -->
            <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-2xl p-6 shadow-sm print:border-gray-300">
                <div class="flex justify-between items-center pb-4 border-b border-gray-100 dark:border-gray-700 print:border-gray-300">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="home" class="h-5 w-5 text-indigo-600 print:hidden"></i>
                        Ringkasan Kost ({{ $months[$month] }} {{ $year }})
                    </h3>
                    <div class="print:hidden">
                        <a href="{{ route('reports.export', ['type' => 'kost', 'month' => $month, 'year' => $year]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 dark:text-indigo-400 text-xs font-bold rounded-xl transition-colors">
                            <i data-lucide="download" class="h-3.5 w-3.5"></i> Ekspor CSV
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-5 text-sm">
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 print:bg-white print:border-gray-200">
                        <span class="text-xs text-gray-500">Penyewa Kost Aktif</span>
                        <span class="block text-xl font-bold mt-1 text-gray-900 dark:text-white">{{ $activeTenantsCount }} Penghuni</span>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 print:bg-white print:border-gray-200">
                        <span class="text-xs text-gray-500">Tingkat Okupansi Kamar</span>
                        <span class="block text-xl font-bold mt-1 text-gray-900 dark:text-white">{{ $occupiedRooms }} / {{ $totalRooms }} Kamar</span>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 print:bg-white print:border-gray-200">
                        <span class="text-xs text-gray-500">Omzet Kost Masuk Kas</span>
                        <span class="block text-xl font-bold mt-1 text-green-600">Rp {{ number_format($kostTotalRevenue, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Laporan Laba Rugi Tab -->
        <div x-show="activeTab === 'labarugi'" id="report-labarugi" class="tab-content-item bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-2xl p-6 sm:p-8 shadow-sm print:border-gray-300">
            <!-- Accounting Report Header -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-left">
                        <h2 class="text-lg font-extrabold uppercase text-gray-900 dark:text-white hidden print:block mb-1">{{ \App\Models\Setting::get('business_name', 'Lestari Laundry & Kost') }}</h2>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white uppercase tracking-wide">Laporan Laba Rugi (Profit & Loss Statement)</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Periode: {{ $months[$month] }} {{ $year }}</p>
                        <p class="text-[10px] text-gray-450 dark:text-gray-500 mt-0.5 italic">Mata Uang: IDR (Rupiah)</p>
                    </div>
                    <div class="print:hidden flex justify-end">
                        <button type="button" onclick="printSection('report-labarugi')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-900/35 dark:hover:bg-blue-900/50 dark:text-blue-400 text-xs font-bold rounded-xl transition-all duration-200 shadow-sm">
                            <i data-lucide="printer" class="h-3.5 w-3.5"></i> Cetak Laba Rugi Saja
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statement Content -->
            <div class="mt-8 max-w-2xl mx-auto text-sm text-gray-800 dark:text-gray-200 space-y-8">
                
                <!-- 1. PENDAPATAN -->
                <div class="space-y-3">
                    <h4 class="font-bold text-gray-900 dark:text-white border-b pb-1">I. PENDAPATAN USAHA</h4>
                    <div class="pl-4 space-y-2">
                        <div class="flex justify-between">
                            <span>Pendapatan Laundry</span>
                            <span class="font-medium">Rp {{ number_format($laundryRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pendapatan Sewa Kamar Kost</span>
                            <span class="font-medium">Rp {{ number_format($kostRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pendapatan Operasional Lainnya</span>
                            <span class="font-medium">Rp {{ number_format($otherRevenue, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between font-bold border-t border-b py-2 pl-4 bg-gray-50/50 dark:bg-gray-900/30">
                        <span class="uppercase">Total Pendapatan Usaha (A)</span>
                        <span>Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- 2. BEBAN OPERASIONAL -->
                <div class="space-y-3">
                    <h4 class="font-bold text-gray-900 dark:text-white border-b pb-1">II. BEBAN OPERASIONAL</h4>
                    <div class="pl-4 space-y-2">
                        <div class="flex justify-between">
                            <span>Beban Listrik</span>
                            <span class="font-medium">Rp {{ number_format($expenses['listrik'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Beban Air / PDAM</span>
                            <span class="font-medium">Rp {{ number_format($expenses['air'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Beban Bahan Detergen & Pewangi</span>
                            <span class="font-medium">Rp {{ number_format($expenses['detergen'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Beban Pembelian & Servis Peralatan</span>
                            <span class="font-medium">Rp {{ number_format($expenses['peralatan'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Beban Operasional Lain-lain</span>
                            <span class="font-medium">Rp {{ number_format($expenses['operasional'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Beban Non-Operasional / Lainnya</span>
                            <span class="font-medium">Rp {{ number_format($expenses['lainnya'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between font-bold border-t border-b py-2 pl-4 bg-gray-50/50 dark:bg-gray-900/30">
                        <span class="uppercase">Total Beban Operasional (B)</span>
                        <span>Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- 3. LABA BERSIH (Accounting double underline style) -->
                <div class="pt-4">
                    <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-200 dark:border-blue-800 flex justify-between items-center text-gray-900 dark:text-white">
                        <div>
                            <span class="text-xs uppercase font-extrabold tracking-wide text-blue-850 dark:text-blue-400">LABA (RUGI) BERSIH OPERASIONAL</span>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Rumus: Pendapatan (A) - Beban (B)</p>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-black {{ $profit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-650 dark:text-red-400' }} border-b-4 border-double border-current">
                                Rp {{ number_format($profit, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Laporan Arus Kas Tab -->
        <div x-show="activeTab === 'cashflow'" class="tab-content-item bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-2xl p-6 sm:p-8 shadow-sm print:border-gray-300">
            <!-- Accounting Report Header -->
            <div class="text-center pb-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wide">Laporan Arus Kas (Statement of Cash Flows)</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Periode: {{ $months[$month] }} {{ $year }}</p>
                <p class="text-xs text-gray-450 mt-0.5 italic">Metode Pelaporan: Langsung (Direct Method)</p>
            </div>

            <!-- Cash Flow Content -->
            <div class="mt-8 max-w-2xl mx-auto text-sm text-gray-800 dark:text-gray-200 space-y-8">
                <!-- Inflows -->
                <div class="space-y-3">
                    <h4 class="font-bold text-gray-900 dark:text-white border-b pb-1">1. ARUS KAS MASUK (CASH INFLOWS)</h4>
                    <div class="pl-4 space-y-2">
                        <div class="flex justify-between">
                            <span>Penerimaan Kas - Tunai (Cash)</span>
                            <span class="font-medium">Rp {{ number_format($cashInflow, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Penerimaan Kas - Transfer Bank</span>
                            <span class="font-medium">Rp {{ number_format($transferInflow, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Penerimaan Kas - Dompet Digital (E-Wallet / QRIS)</span>
                            <span class="font-medium">Rp {{ number_format($ewalletInflow, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between font-bold border-t border-b py-2 pl-4 bg-gray-50/50 dark:bg-gray-900/30">
                        <span>Total Arus Kas Masuk</span>
                        <span>Rp {{ number_format($cashInflow + $transferInflow + $ewalletInflow, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Outflows -->
                <div class="space-y-3">
                    <h4 class="font-bold text-gray-900 dark:text-white border-b pb-1">2. ARUS KAS KELUAR (CASH OUTFLOWS)</h4>
                    <div class="pl-4 space-y-2">
                        <div class="flex justify-between">
                            <span>Pembayaran Kas - Tunai (Cash)</span>
                            <span class="font-medium">Rp {{ number_format($cashOutflow, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pembayaran Kas - Transfer Bank</span>
                            <span class="font-medium">Rp {{ number_format($transferOutflow, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pembayaran Kas - Dompet Digital (E-Wallet / QRIS)</span>
                            <span class="font-medium">Rp {{ number_format($ewalletOutflow, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between font-bold border-t border-b py-2 pl-4 bg-gray-50/50 dark:bg-gray-900/30">
                        <span>Total Arus Kas Keluar</span>
                        <span>Rp {{ number_format($cashOutflow + $transferOutflow + $ewalletOutflow, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Net Increase/Decrease in Cash -->
                @php
                    $netCashFlow = ($cashInflow + $transferInflow + $ewalletInflow) - ($cashOutflow + $transferOutflow + $ewalletOutflow);
                @endphp
                <div class="pt-4">
                    <div class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 flex justify-between items-center text-gray-900 dark:text-white">
                        <div>
                            <span class="text-xs uppercase font-extrabold tracking-wide text-green-800 dark:text-green-400">KENAIKAN (PENURUNAN) BERSIH KAS</span>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5 font-medium">Likuiditas Operasional bulan {{ $months[$month] }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-black {{ $netCashFlow >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-650 dark:text-red-400' }} border-b-4 border-double border-current">
                                Rp {{ number_format($netCashFlow, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Buku Piutang Tab -->
        <div x-show="activeTab === 'piutang'" class="tab-content-item space-y-6">
            <!-- Piutang Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-2xl p-5 shadow-sm print:border-gray-300">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Piutang Laundry Berjalan</span>
                    <span class="block text-xl font-bold mt-2 text-red-600">Rp {{ number_format($laundryReceivables, 0, ',', '.') }}</span>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-2xl p-5 shadow-sm print:border-gray-300">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Piutang Kamar Kost</span>
                    <span class="block text-xl font-bold mt-2 text-red-600">Rp {{ number_format($kostReceivables, 0, ',', '.') }}</span>
                </div>
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl p-5 shadow-md print:bg-white print:text-black print:border print:border-gray-300">
                    <span class="text-xs font-semibold text-slate-400 print:text-gray-500 uppercase tracking-wider">Total Piutang Usaha (Buku Besar)</span>
                    <span class="block text-xl font-bold mt-2 text-blue-400 print:text-blue-600">Rp {{ number_format($totalReceivables, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- List of Unpaid Laundry Orders -->
            <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-2xl p-6 shadow-sm print:border-gray-300">
                <h3 class="text-base font-bold text-gray-900 dark:text-white border-b pb-3 flex items-center gap-2">
                    <i data-lucide="shopping-cart" class="h-5 w-5 text-blue-600 print:hidden"></i>
                    Buku Pembantu Piutang: Laundry
                </h3>
                <div class="overflow-x-auto mt-4">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">
                                <th class="px-4 py-3">No. Order</th>
                                <th class="px-4 py-3">Nama Pelanggan</th>
                                <th class="px-4 py-3">Tanggal Order</th>
                                <th class="px-4 py-3">Total Tagihan</th>
                                <th class="px-4 py-3">Sudah Dibayar</th>
                                <th class="px-4 py-3 text-red-600 font-bold">Sisa Piutang</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                            @forelse($unpaidLaundryOrders as $order)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-blue-650 dark:text-blue-455">
                                        <a href="{{ route('laundry.orders.show', $order) }}" class="hover:underline">{{ $order->order_number }}</a>
                                    </td>
                                    <td class="px-4 py-3 font-medium">{{ $order->customer->name }}</td>
                                    <td class="px-4 py-3">{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="px-4 py-3">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-green-600">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 font-bold text-red-600">Rp {{ number_format($order->total_price - $order->paid_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 capitalize">
                                        <span class="px-2 py-0.5 rounded-full font-semibold text-[10px] 
                                            @if($order->payment_status === 'dp') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 
                                            @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif">
                                            {{ $order->payment_status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500 italic">
                                        Tidak ada piutang laundry terdaftar pada bulan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- List of Unpaid Tenant Payments -->
            <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-2xl p-6 shadow-sm print:border-gray-300">
                <h3 class="text-base font-bold text-gray-900 dark:text-white border-b pb-3 flex items-center gap-2">
                    <i data-lucide="home" class="h-5 w-5 text-indigo-600 print:hidden"></i>
                    Buku Pembantu Piutang: Kamar Kost
                </h3>
                <div class="overflow-x-auto mt-4">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">
                                <th class="px-4 py-3">Nama Penghuni</th>
                                <th class="px-4 py-3">No Kamar</th>
                                <th class="px-4 py-3">Tipe Tagihan</th>
                                <th class="px-4 py-3">Jatuh Tempo/Pembuatan</th>
                                <th class="px-4 py-3 text-red-600 font-bold">Jumlah Piutang</th>
                                <th class="px-4 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                            @forelse($unpaidTenantPayments as $payment)
                                <tr>
                                    <td class="px-4 py-3 font-semibold">{{ $payment->tenant->name }}</td>
                                    <td class="px-4 py-3">Kamar {{ $payment->tenant->room->room_number }}</td>
                                    <td class="px-4 py-3 capitalize">{{ $payment->payment_type }}</td>
                                    <td class="px-4 py-3">{{ $payment->created_at->format('d M Y') }}</td>
                                    <td class="px-4 py-3 font-bold text-red-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-gray-500 max-w-xs truncate" title="{{ $payment->notes }}">{{ $payment->notes ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 italic">
                                        Tidak ada piutang kamar kost terdaftar pada bulan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        /* Bypass Alpine.js styling and print all sections sequentially by default */
        .tab-content-item {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            page-break-after: always;
        }
        .tab-content-item:last-child {
            page-break-after: avoid;
        }
        
        /* Single section printing override */
        body.print-only-target .print-document-header {
            display: none !important;
        }
        body.print-only-target .tab-content-item:not(.print-target-element) {
            display: none !important;
        }
        body.print-only-target .tab-content-item.print-target-element {
            display: block !important;
            page-break-after: avoid !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        
        body {
            background-color: white !important;
            color: black !important;
        }
        main {
            padding: 0 !important;
        }
    }
</style>

<script>
    function printSection(sectionId) {
        const element = document.getElementById(sectionId);
        if (!element) return;
        
        // Add single-section printing classes
        document.body.classList.add('print-only-target');
        element.classList.add('print-target-element');
        
        // Trigger standard print
        window.print();
        
        // Remove classes after print dialog opens
        document.body.classList.remove('print-only-target');
        element.classList.remove('print-target-element');
    }
</script>
@endsection

