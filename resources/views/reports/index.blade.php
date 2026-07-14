@extends('layouts.app')

@section('title', 'Laporan Operasional & Keuangan')

@section('content')
<div class="space-y-8 print:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 print:hidden">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Laporan Ringkasan Bisnis</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Unduh ekspor CSV Excel atau cetak lembar laporan PDF.</p>
        </div>
        <div>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-950 hover:bg-gray-900 text-white font-medium rounded-xl text-sm shadow-sm transition-colors">
                <i data-lucide="printer" class="h-4 w-4"></i> Cetak Laporan (PDF)
            </button>
        </div>
    </div>

    <!-- Printable Header (Only visible on print) -->
    <div class="hidden print:block text-center border-b pb-4">
        <h1 class="text-2xl font-bold text-gray-900">{{ \App\Models\Setting::get('business_name', 'Lestari Laundry & Kost') }}</h1>
        <p class="text-sm text-gray-500">{{ \App\Models\Setting::get('business_address', 'Jakarta, Indonesia') }}</p>
        <h2 class="text-lg font-semibold mt-4">LAPORAN RINGKASAN OPERASIONAL & KEUANGAN</h2>
        <p class="text-xs text-gray-400 mt-1">Dicetak pada: {{ date('d M Y, H:i') }}</p>
    </div>

    <!-- Section 1: Laundry Report -->
    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm space-y-4 print:border-0 print:shadow-none">
        <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 print:border-gray-300">
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i data-lucide="shopping-cart" class="h-5 w-5 text-blue-600 print:hidden"></i>
                Ringkasan Laundry
            </h3>
            <div class="print:hidden">
                <a href="{{ route('reports.export', ['type' => 'laundry']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 dark:text-blue-400 text-xs font-bold rounded-xl transition-colors">
                    <i data-lucide="download" class="h-3.5 w-3.5"></i> Ekspor Excel (CSV)
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 print:bg-white print:border-gray-200">
                <span class="text-xs text-gray-500">Total Order Laundry</span>
                <span class="block text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $laundryOrdersCount }} Order</span>
            </div>
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 print:bg-white print:border-gray-200">
                <span class="text-xs text-gray-500">Total Volume Cucian</span>
                <span class="block text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $laundryTotalWeight }} kg</span>
            </div>
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 print:bg-white print:border-gray-200">
                <span class="text-xs text-gray-500">Pendapatan Laundry</span>
                <span class="block text-2xl font-bold mt-1 text-green-600 dark:text-green-400">Rp {{ number_format($laundryTotalRevenue, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Section 2: Boarding House Report -->
    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm space-y-4 print:border-0 print:shadow-none">
        <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 print:border-gray-300">
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i data-lucide="home" class="h-5 w-5 text-indigo-600 print:hidden"></i>
                Ringkasan Kost
            </h3>
            <div class="print:hidden">
                <a href="{{ route('reports.export', ['type' => 'kost']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 dark:text-indigo-400 text-xs font-bold rounded-xl transition-colors">
                    <i data-lucide="download" class="h-3.5 w-3.5"></i> Ekspor Excel (CSV)
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 print:bg-white print:border-gray-200">
                <span class="text-xs text-gray-500">Penyewa Kost Aktif</span>
                <span class="block text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $activeTenantsCount }} Orang</span>
            </div>
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 print:bg-white print:border-gray-200">
                <span class="text-xs text-gray-500">Okupansi Kamar</span>
                <span class="block text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $occupiedRooms }} / {{ $totalRooms }} Kamar</span>
            </div>
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 print:bg-white print:border-gray-200">
                <span class="text-xs text-gray-500">Pendapatan Kost</span>
                <span class="block text-2xl font-bold mt-1 text-green-600 dark:text-green-400">Rp {{ number_format($kostTotalRevenue, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Section 3: Finance Cash Flow Report -->
    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm space-y-4 print:border-0 print:shadow-none">
        <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 print:border-gray-300">
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i data-lucide="wallet" class="h-5 w-5 text-green-600 print:hidden"></i>
                Ringkasan Cash Flow (Arus Kas)
            </h3>
            <div class="print:hidden">
                <a href="{{ route('reports.export', ['type' => 'finance']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 hover:bg-green-100 text-green-600 dark:bg-green-900/30 dark:hover:bg-green-900/50 dark:text-green-400 text-xs font-bold rounded-xl transition-colors">
                    <i data-lucide="download" class="h-3.5 w-3.5"></i> Ekspor Excel (CSV)
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 print:bg-white print:border-gray-200">
                <span class="text-xs text-gray-500">Total Arus Pemasukan (Inflow)</span>
                <span class="block text-2xl font-bold mt-1 text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
            </div>
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 print:bg-white print:border-gray-200">
                <span class="text-xs text-gray-500">Total Arus Pengeluaran (Outflow)</span>
                <span class="block text-2xl font-bold mt-1 text-red-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
            </div>
            <div class="p-4 rounded-xl bg-gradient-to-br from-slate-900 to-slate-800 text-white border border-slate-700 print:from-white print:to-white print:text-gray-950 print:border-gray-200">
                <span class="text-xs text-slate-400 print:text-gray-500">Sisa Laba Bersih (Profit)</span>
                <span class="block text-2xl font-bold mt-1 text-blue-400 print:text-blue-600">Rp {{ number_format($profit, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body {
            background-color: white !important;
            color: black !important;
        }
        main {
            padding: 0 !important;
        }
    }
</style>
@endsection
